<?php

namespace App\Entity;

use App\Repository\UsersObjectsServicesBundlesRepository;
use App\Traits\AdminstampableTrait;
use App\Traits\IdTrait;
use App\Traits\TimestampableTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;

#[ORM\Table('users_objects_services_bundles')]
#[ORM\Entity(repositoryClass: UsersObjectsServicesBundlesRepository::class)]
#[ORM\HasLifecycleCallbacks]
class UsersObjectsServicesBundles extends BaseEntity
{
    use IdTrait;
    use TimestampableTrait;
    use AdminstampableTrait;

    /**
     * This is the owning side.
     * @see UsersObjects::$users_objects_services_bundles
     * @var \Proxies\__CG__\App\Entity\UsersObjects|UsersObjects
     */
    #[ORM\ManyToOne(targetEntity: UsersObjects::class, inversedBy: 'users_objects_services_bundles')]
    #[ORM\JoinColumn(name: 'users_object_id', referencedColumnName: 'id', onDelete: 'RESTRICT')]
    public $users_object;

    /**
     * @see UsersObjectsServices::$users_objects_services_bundles
     * @var PersistentCollection|UsersObjectsServices[]
     */
    #[ORM\OneToMany(targetEntity: UsersObjectsServices::class, mappedBy: 'users_objects_services_bundles')]
    public $users_objects_services;

    /**
     * @see Invoices::$users_objects_services_bundles
     * @var PersistentCollection|Invoices[]
     */
    #[ORM\OneToMany(targetEntity: Invoices::class, mappedBy: 'users_objects_services_bundles')]
    public $invoices;

    /**
     * Imtinai
     */
    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: false, options: ['default' => 'CURRENT_DATE'])]
    public \DateTime $active_from;

    /**
     * Imtinai
     */
    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    public ?\DateTime $active_to = null;

    public function __construct()
    {
        parent::__construct();
        $this->active_from = new \DateTime();
    }

    /**
     * @return UsersObjects|PersistentCollection
     */
    public function getUsersObject()
    {
        return $this->users_object;
    }

    public function getServicesCount(): int
    {
        return $this->users_objects_services->count();
    }

    public function __toString()
    {
        return '[#' . $this->getId() . '] ' . $this->getUsersObject()->__toString() . ', ' . $this->created_at->format('Y-m-d H:i');
    }

    public function isBundleActive(): bool
    {
        return !$this->active_to || $this->active_to > new \DateTime();
    }

    /**
     * @return UsersObjectsServices[]|PersistentCollection
     */
    public function getUsersObjectsServices(): PersistentCollection|array
    {
        return $this->users_objects_services;
    }

    /**
     * @return Invoices[]|PersistentCollection
     */
    public function getInvoices(): PersistentCollection|array
    {
        return $this->invoices;
    }
}
