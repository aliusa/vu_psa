<?php

namespace App\Entity;

use App\Repository\InvoicesRepository;
use App\Traits\IdTrait;
use App\Traits\TimestampableTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as AssertDoctrine;
use Symfony\Component\Validator\Constraints as AssertValidator;

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
    #[ORM\ManyToOne(targetEntity: UsersObjectsServicesBundles::class, inversedBy: 'users_objects_services')]
    #[ORM\JoinColumn(name: 'users_objects_services_bundles_id', referencedColumnName: 'id', onDelete: 'RESTRICT')]
    public $users_objects_services_bundles;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true, options: [])]
    public $due_date;

    #[ORM\Column(type: Types::BOOLEAN, nullable: false, options: ['default' => false])]
    public $is_paid;

    #[ORM\Column(type: Types::STRING, nullable: false, options: [])]
    public $series;

    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::STRING, nullable: false, options: [])]
    public $no;

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

    public function getFormattedSeries(): string
    {
        return vsprintf("%s-%05d", [$this->series, $this->getNo()]);
    }

    public function getInvoiceServices()
    {
        return $this->users_objects_services_bundles->getUsersObjectsServices();
    }

    public function getNo(): string
    {
        return vsprintf("%05d", [$this->no]);
    }

    public function getInvoiceTotal(): int
    {
        $total = 0;
        foreach ($this->getInvoiceServices() as $invoiceService) {
            $total += $invoiceService->total_price;
        }
        return $total;
    }
}
