<?php

namespace App\Entity;

use App\Repository\UsersObjectsServicesRepository;
use App\Traits\IdTrait;
use App\Traits\TimestampableTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\Validator\Constraints AS Assert;

#[ORM\Table('users_objects_services')]
#[ORM\Entity(repositoryClass: UsersObjectsServicesRepository::class)]
#[ORM\HasLifecycleCallbacks]
class UsersObjectsServices extends BaseEntity
{
    use IdTrait;
    use TimestampableTrait;

    /**
     * This is the owning side.
     * @see UsersObjectsServicesBundles::$
     * @var PersistentCollection|Services
     */
    #[ORM\ManyToOne(targetEntity: UsersObjectsServicesBundles::class, inversedBy: 'users_objects_services')]
    #[ORM\JoinColumn(name: 'users_objects_services_bundles_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    public $users_objects_services_bundles;

    /**
     * This is the owning side.
     * @see Services::$users_objects_services
     * @var PersistentCollection|Services
     */
    #[ORM\ManyToOne(targetEntity: Services::class, inversedBy: 'users_objects_services')]
    #[ORM\JoinColumn(name: 'services_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    public $services;

    #[Assert\Range(min: 0, max: 100)]
    #[ORM\Column(type: Types::INTEGER, nullable: false)]
    public int $amount;

    #[Assert\Range(min: 0, max: 10000)]
    #[ORM\Column(type: Types::INTEGER, nullable: false)]
    public int $unit_price;

    #[Assert\Range(min: 0, max: 100000)]
    #[ORM\Column(type: Types::INTEGER, nullable: false)]
    public int $total_price;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true, options: ['default' => 'CURRENT_TIMESTAMP'])]
    public $active_to;

    public function __toString()
    {
        return implode(' - ', ["[#{$this->getId()}]", ]);
    }
}
