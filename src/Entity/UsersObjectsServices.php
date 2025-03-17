<?php

namespace App\Entity;

use App\Repository\UsersObjectsServicesRepository;
use App\Traits\IdTrait;
use App\Traits\TimestampableTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\Validator\Constraints AS AssertValidator;

#[ORM\Table('users_objects_services')]
#[ORM\Entity(repositoryClass: UsersObjectsServicesRepository::class)]
#[ORM\HasLifecycleCallbacks]
class UsersObjectsServices extends BaseEntity
{
    use IdTrait;
    use TimestampableTrait;

    /**
     * This is the owning side.
     * @see UsersObjectsServicesBundles::$users_objects_services
     * @var PersistentCollection|UsersObjectsServicesBundles
     */
    #[ORM\ManyToOne(targetEntity: UsersObjectsServicesBundles::class, inversedBy: 'users_objects_services')]
    #[ORM\JoinColumn(name: 'users_objects_services_bundles_id', referencedColumnName: 'id', onDelete: 'RESTRICT')]
    public $users_objects_services_bundles;

    /**
     * This is the owning side.
     * @see Services::$users_objects_services
     * @var PersistentCollection|Services
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
    #[Assert\Range(min: 0, max: 10000)]
    #[ORM\Column(type: Types::INTEGER, nullable: false, generated: 'ALWAYS', columnDefinition: "unit_price + unit_adjustments")]
    public int $unit_total;

    /**
     * @var int Vnt. kaina su PVM * kiekis
     */
    #[AssertValidator\Range(min: 0, max: 100000)]
    #[ORM\Column(type: Types::INTEGER, nullable: false)]
    public int $total_price;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true, options: ['default' => 'CURRENT_TIMESTAMP'])]
    public $active_to;

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
}
