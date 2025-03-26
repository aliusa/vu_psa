<?php

namespace App\Entity;

use App\Repository\AdministratorsRepository;
use App\Traits\AdminstampableTrait;
use App\Traits\IdTrait;
use App\Traits\TimestampableTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as AssertDoctrine;
use Symfony\Component\Validator\Constraints as AssertValidator;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: AdministratorsRepository::class)]
#[AssertDoctrine\UniqueEntity('email')]
#[ORM\HasLifecycleCallbacks]
class Administrators extends BaseEntity implements UserInterface, PasswordAuthenticatedUserInterface
{
    use IdTrait;
    use TimestampableTrait;
    use AdminstampableTrait;

    #[AssertValidator\Length(max: 100)]
    #[ORM\Column(type: Types::STRING, length: 100, unique: true)]
    public $email;

    #[ORM\Column(type: Types::JSON)]
    private $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column(type: Types::STRING, nullable: true)]
    public $password;

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return $this->password ?? '';
    }

    public function setPassword(?string $password = null): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
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
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUserIdentifier(): string
    {
        return $this->getUsername();
    }

    public function __toString(): string
    {
        return '[#' . $this->id . '] ' . $this->email;
    }

}
