<?php

namespace App\Entity;

use App\Repository\UsersObjectsServicesPromotionsRepository;
use App\Traits\AdminstampableTrait;
use App\Traits\IdTrait;
use App\Traits\TimestampableTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints as AssertDoctrine;
use Symfony\Component\Validator\Constraints as AssertValidator;

#[ORM\Table('users_objects_services_promotions')]
#[ORM\Entity(repositoryClass: UsersObjectsServicesPromotionsRepository::class)]
#[ORM\HasLifecycleCallbacks]
class UsersObjectsServicesPromotions extends BaseEntity
{
    public const MULTIPLE = false;

    use IdTrait;
    use TimestampableTrait;
    use AdminstampableTrait;

    /**
     * This is the owning side.
     * @see UsersObjectsServices::$users_objects_services_promotions
     * @var \Proxies\__CG__\App\Entity\UsersObjectsServices|UsersObjectsServices
     */
    #[ORM\ManyToOne(targetEntity: UsersObjectsServices::class, cascade: [], inversedBy: 'users_objects_services_promotions')]
    #[ORM\JoinColumn(name: 'users_objects_services_id', referencedColumnName: 'id', onDelete: 'RESTRICT')]
    public $users_objects_services;

    /**
     * This is the owning side.
     * @see ServicesPromotions::$users_objects_services_promotions
     * @var \Proxies\__CG__\App\Entity\ServicesPromotions|ServicesPromotions
     */
    #[ORM\ManyToOne(targetEntity: ServicesPromotions::class, cascade: [], inversedBy: 'users_objects_services_promotions')]
    #[ORM\JoinColumn(name: 'services_promotions_id', referencedColumnName: 'id', onDelete: 'RESTRICT')]
    public $services_promotions;

    public function __construct()
    {
        parent::__construct();
    }

    public function __toString(): string
    {
        return implode(' - ', ["[#{$this->getId()}]", ]);
    }
}
