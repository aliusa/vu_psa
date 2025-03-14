<?php

namespace App\Entity;

use App\Repository\UsersObjectsRepository;
use App\Traits\IdTrait;
use App\Traits\TimestampableTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints as AssertDoctrine;
use Symfony\Component\Validator\Constraints as AssertValidator;

#[ORM\Table('users_objects')]
#[ORM\Entity(repositoryClass: UsersObjectsRepository::class)]
#[ORM\HasLifecycleCallbacks]
class UsersObjects extends BaseEntity
{
    use IdTrait;
    use TimestampableTrait;

    /**
     * This is the owning side.
     * @see Users::$users_objects
     * @var Users
     */
    #[ORM\ManyToOne(targetEntity: Users::class, inversedBy: 'users_objects')]
    #[ORM\JoinColumn(name: 'users_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    public $users;

    /**
     * This is the owning side.
     * @see Users::$users_objects
     * @var PersistentCollection|Users
     */
    #[ORM\ManyToOne(targetEntity: Countries::class, inversedBy: 'users_objects')]
    #[ORM\JoinColumn(name: 'country_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    public $country;

    #[AssertValidator\Length(max: 100)]
    #[ORM\Column(type: Types::STRING, length: 100, nullable: true)]
    public ?string $city;

    #[AssertValidator\Length(max: 255)]
    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    public ?string $street;

    #[AssertValidator\Length(max: 100)]
    #[ORM\Column(type: Types::STRING, length: 100, nullable: true)]
    public ?string $house;

    #[AssertValidator\Length(max: 10)]
    #[ORM\Column(type: Types::STRING, length: 10, nullable: true)]
    public ?string $flat;

    #[AssertValidator\Length(min: 5, max: 5)]
    #[ORM\Column(type: Types::STRING, length: 5, nullable: true)]
    public ?string $zip;

    /**
     * One User have Many UsersObjects.
     * @see UsersObjectsServicesBundles::$users_object
     * @var PersistentCollection|UsersObjectsServicesBundles[]
     */
    #[ORM\OneToMany(targetEntity: UsersObjectsServicesBundles::class, mappedBy: 'users_object')]
    #[ORM\JoinColumn(name: 'users_objects_services_bundles_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    public $users_objects_services_bundles;

    public function __toString()
    {
        return implode(' - ', ["[#{$this->getId()}]", $this->users->getFullName(), $this->city, $this->street, $this->house.'-'.$this->flat, $this->zip]);
    }

    public function getActiveUsersObjectsServicesBundlesCount(): int
    {
        return $this->users_objects_services_bundles->filter(static function(UsersObjectsServicesBundles $usersObjectsServicesBundles) {
            return $usersObjectsServicesBundles->isActive();
        })->count();
    }

    public function getInactiveUsersObjectsServicesBundlesCount(): int
    {
        return $this->users_objects_services_bundles->filter(static function(UsersObjectsServicesBundles $usersObjectsServicesBundles) {
            return !$usersObjectsServicesBundles->isActive();
        })->count();
    }

    /**
     * @return UsersObjectsServicesBundles[]|PersistentCollection
     */
    public function getUsersObjectsServicesBundles(): PersistentCollection|array
    {
        return $this->users_objects_services_bundles;
    }

    public function getTitle():string
    {
        return vsprintf('%s %s %s, LT-%s', [$this->city, $this->street, $this->house.'-'.$this->flat, $this->zip]);
    }
}
