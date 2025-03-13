<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use App\Traits\IdTrait;
use App\Traits\TimestampableTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints as AssertDoctrine;
use Symfony\Component\Validator\Constraints as AssertValidator;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Table('users')]
#[ORM\Entity(repositoryClass: UsersRepository::class)]
#[AssertDoctrine\UniqueEntity('email')]
#[ORM\HasLifecycleCallbacks]
class Users extends BaseEntity implements UserInterface, PasswordAuthenticatedUserInterface
{
    use IdTrait;
    use TimestampableTrait;

    #[AssertValidator\Length(max: 255)]
    #[ORM\Column(type: Types::STRING, length: 255, nullable: false, unique: true)]
    public ?string $email = null;

    #[AssertValidator\Length(max: 255)]
    #[ORM\Column(type: Types::STRING, length: 255, nullable: false)]
    public ?string $password = null;

    #[AssertValidator\Length(max: 255)]
    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    public ?string $phone;

    #[AssertValidator\Length(max: 255)]
    #[ORM\Column(type: Types::STRING, length: 255, nullable: false)]
    public ?string $first_name = null;

    #[AssertValidator\Length(max: 255)]
    #[ORM\Column(type: Types::STRING, length: 255, nullable: false)]
    public ?string $last_name = null;

    #[ORM\Column(type: Types::JSON)]
    private array $roles = [];

    /**
     * One User have Many UsersObjects.
     * @see UsersObjects::$users
     * @var PersistentCollection|UsersObjects[]
     */
    #[ORM\OneToMany(targetEntity: UsersObjects::class, mappedBy: 'users', cascade: ['persist', 'remove'])]
    #[ORM\OrderBy(['id' => 'DESC'])]
    public $users_objects;

    public bool $isNew = false;

    public function __construct()
    {
        parent::__construct(...func_get_args());
        $this->users_objects = new \Doctrine\Common\Collections\ArrayCollection();
        $this->isNew = true;
    }

    public function __toString()
    {
        return '[#' . $this->id . '] ' . $this->email;
    }

    public function asArray(): array
    {
        return [
            'id' => $this->getId(),
            'phone' => $this->phone ?? '',
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
        ];
    }

    public function getPassword(): ?string
    {
        return $this->password ?? '';
    }
    public function setPassword(?string $password = null)
    {
        $this->password = $password;
    }

    public function getSalt()
    {
        return null;
    }

    public function getUsername()
    {
        return $this->email;
    }

    public function getConfirmedEmail():?string
    {
        return $this->email;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function eraseCredentials(): void
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        return $this->getFullName();
    }

    public function getFullName():string
    {
        return $this->first_name . ' ' . $this->last_name;
    }
    public function getOwnedAmount():float{
        //todo 2025-03-05 23:48 alius:
        return 5.15;
    }
    public function getUsersOjectsList():array{
        return $this->users_objects->toArray();
    }
}
