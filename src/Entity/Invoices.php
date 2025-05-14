<?php

namespace App\Entity;

use App\Repository\InvoicesRepository;
use App\Traits\IdTrait;
use App\Traits\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints as AssertDoctrine;

#[ORM\Table('invoices')]
#[ORM\Entity(repositoryClass: InvoicesRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[AssertDoctrine\UniqueEntity(['series', 'no'])]
class Invoices extends BaseEntity
{
    use IdTrait;
    use TimestampableTrait;

    /**
     * This is the owning side.
     * @see UsersObjectsServicesBundles::$invoices
     * @var UsersObjectsServicesBundles
     */
    #[ORM\ManyToOne(targetEntity: UsersObjectsServicesBundles::class, cascade: [], inversedBy: 'invoices')]
    #[ORM\JoinColumn(name: 'users_objects_services_bundles_id', referencedColumnName: 'id', onDelete: 'RESTRICT')]
    public $users_objects_services_bundles;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: false, options: ['default' => 'CURRENT_DATE'])]
    public \DateTime $period_start;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: false, options: [])]
    public \DateTime $period_end;

    /**
     * Ne imtinai
     */
    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true, options: [])]
    public $due_date;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE, nullable: true)]
    public ?\DateTime $is_paid = null;

    #[ORM\Column(type: Types::STRING, nullable: false, options: [])]
    public $series;

    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, nullable: false, options: [])]
    public $no;

    /**
     * One Invoice have Many payments.
     * @see Payments::$invoices
     * @var PersistentCollection|Payments
     */
    #[ORM\OneToMany(targetEntity: Payments::class, mappedBy: 'invoices')]
    public $payments;

    public function __construct()
    {
        parent::__construct();
        $this->payments = new ArrayCollection();
    }

    public function __toString()
    {
        return implode(' - ', ["[#{$this->getId()}]", $this->getFormattedSeries()]);
    }

    public function getUser(): Users
    {
        return $this->users_objects_services_bundles->users_object->users;
    }
    public function getUsersObjectsServicesBundles(): UsersObjectsServicesBundles
    {
        return $this->users_objects_services_bundles;
    }

    public function isDuePassed(): bool
    {
        return !$this->is_paid && $this->due_date < new \DateTime();
    }

    public function isPayedPassedDue(): bool
    {
        return $this->is_paid && $this->due_date < $this->is_paid;
    }

    /**
     * @return string SAS-00001
     */
    public function getFormattedSeries(): string
    {
        return vsprintf("%s-%05d", [$this->series, $this->getNo()]);
    }

    /**
     * @return ArrayCollection|UsersObjectsServices[]
     */
    public function getInvoiceServices()
    {
        return $this->users_objects_services_bundles->getUsersObjectsServices()->filter(function (UsersObjectsServices $usersObjectsServices) {
            return $usersObjectsServices->active_from <= $this->period_end
                && $this->period_start <= $usersObjectsServices->active_to
                ;
        });
    }

    public function getNo(): string
    {
        return vsprintf("%05d", [$this->no]);
    }

    public function getInvoiceTotal(): int
    {
        $total = 0;
        foreach ($this->getInvoiceServices() as $usersObjectsService) {
            $total += $usersObjectsService->getAdjustedTotalPrice($this);
        }
        return $total;
    }

    /**
     * Už kurį periodą išrašyta sąskaita.
     *
     * @return string "2025 m. Vasaris"
     * @throws \DateInvalidOperationException
     */
    public function getPeriod(): string
    {
        $month = match ($this->period_start->format('n')) {
            '1' => 'Sausis',
            '2' => 'Vasaris',
            '3' => 'Kovas',
            '4' => 'Balandis',
            '5' => 'Gegužis',
            '6' => 'Birželis',
            '7' => 'Liepa',
            '8' => 'Rugpjūtis',
            '9' => 'Rugsėjis',
            '10' => 'Spalis',
            '11' => 'Lapkritis',
            '12' => 'Gruodis',
        };

        return $this->period_start->format('Y') . ' m. ' . $month;
    }
}
