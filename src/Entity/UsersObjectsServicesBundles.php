<?php

namespace App\Entity;

use App\Repository\UsersObjectsServicesBundlesRepository;
use App\Traits\IdTrait;
use App\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;

#[ORM\Table('users_objects_services_bundles')]
#[ORM\Entity(repositoryClass: UsersObjectsServicesBundlesRepository::class)]
#[ORM\HasLifecycleCallbacks]
class UsersObjectsServicesBundles extends BaseEntity
{
    use IdTrait;
    use TimestampableTrait;

    /**
     * This is the owning side.
     * @see UsersObjects::$users_objects_services_bundles
     * @var PersistentCollection|UsersObjects
     */
    #[ORM\ManyToOne(targetEntity: UsersObjects::class, inversedBy: 'users_objects_services_bundles')]
    #[ORM\JoinColumn(name: 'users_object_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    public $users_object;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    public ?\DateTimeImmutable $active_to = null;

    /**
     * @return UsersObjects|PersistentCollection
     */
    public function getUsersObject()
    {
        return $this->users_object;
    }

    public function getServicesCount(): int
    {
        //todo 2025-03-06 01:26 alius:
        return 3;
    }

    public function __toString()
    {
        return '[#' . $this->getId() . '] ' . $this->getUsersObject()->__toString() . ', ' . $this->created_at->format('Y-m-d H:i');
    }

    public function isActive(): bool
    {
        return $this->active_to > new \DateTime();
    }
}
