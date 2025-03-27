<?php

namespace App\Entity;

use App\Repository\UsersObjectsServicesRepository;
use App\Traits\AdminstampableTrait;
use App\Traits\IdTrait;
use App\Traits\TimestampableTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints AS AssertValidator;

#[ORM\Table('users_objects_services')]
#[ORM\Entity(repositoryClass: UsersObjectsServicesRepository::class)]
#[ORM\HasLifecycleCallbacks]
class UsersObjectsServices extends BaseEntity
{
    use IdTrait;
    use TimestampableTrait;
    use AdminstampableTrait;

    /**
     * This is the owning side.
     * @see UsersObjectsServicesBundles::$users_objects_services
     * @var \Proxies\__CG__\App\Entity\UsersObjectsServicesBundles|UsersObjectsServicesBundles
     */
    #[ORM\ManyToOne(targetEntity: UsersObjectsServicesBundles::class, inversedBy: 'users_objects_services')]
    #[ORM\JoinColumn(name: 'users_objects_services_bundles_id', referencedColumnName: 'id', onDelete: 'RESTRICT')]
    public $users_objects_services_bundles;

    /**
     * This is the owning side.
     * @see Services::$users_objects_services
     * @var \Proxies\__CG__\App\Entity\Services|Services
     */
    #[ORM\ManyToOne(targetEntity: Services::class, inversedBy: 'users_objects_services')]
    #[ORM\JoinColumn(name: 'services_id', referencedColumnName: 'id', onDelete: 'RESTRICT')]
    public $services;

    #[AssertValidator\Range(min: 1, max: 100)]
    #[ORM\Column(type: Types::INTEGER, nullable: false)]
    public int $amount = 1;

    /**
     * @var int Vnt. kaina be PVM
     */
    #[AssertValidator\Range(min: 0, max: 10000)]
    #[ORM\Column(type: Types::INTEGER, nullable: false)]
    public int $unit_price;

    /**
     * @var int Vnt. PVM
     */
    #[AssertValidator\Range(min: 0, max: 10000)]
    #[ORM\Column(type: Types::INTEGER, nullable: false, options: ['default' => 0])]
    public int $unit_adjustments;

    /**
     * @var int Vnt. kaina su PVM (unit_price + unit_adjustments)
     */
    #[AssertValidator\Range(min: 0, max: 10000)]
    #[ORM\Column(type: Types::INTEGER, nullable: false, generated: 'ALWAYS', columnDefinition: "unit_price + unit_adjustments")]
    public int $unit_total;

    /**
     * @var int Vnt. kaina su PVM * kiekis
     */
    #[AssertValidator\Range(min: 0, max: 100000)]
    #[ORM\Column(type: Types::INTEGER, nullable: false)]
    public int $total_price;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: false, options: ['default' => 'CURRENT_DATE'])]
    public \DateTime $active_from;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: false, options: [])]
    public \DateTime $active_to;

    public function __construct()
    {
        parent::__construct();

        $this->active_from = new \DateTime();
        if (isset($this->unit_price)) {
            $this->unit_adjustments = $this->unit_price * 0.21;
        }
    }

    public function __toString()
    {
        return implode(' - ', ["[#{$this->getId()}]", ]);
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function calculateUnitTotal(): void
    {
        $this->unit_total = $this->unit_price + $this->unit_adjustments;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function calculateTotalPrice(): void
    {
        $this->total_price = $this->unit_total * ($this->amount ?? 1);
    }

    public function isServiceActive(): bool
    {
        return $this->active_to > new \DateTime();
    }

    public function isFullPeriod(Invoices $invoices): bool
    {
        return $this->active_from <= $invoices->period_start
            && $this->active_to >= $invoices->period_end;
            ;
    }
}
