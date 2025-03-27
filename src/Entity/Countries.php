<?php

namespace App\Entity;

use App\Repository\CountriesRepository;
use App\Traits\AdminstampableTrait;
use App\Traits\IdTrait;
use App\Traits\TimestampableTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints as AssertDoctrine;
use Symfony\Component\Validator\Constraints as AssertValidator;

#[ORM\Table('countries')]
#[ORM\Entity(repositoryClass: CountriesRepository::class)]
#[AssertDoctrine\UniqueEntity('title')]
#[ORM\HasLifecycleCallbacks]
class Countries extends BaseEntity
{
    use IdTrait;
    use TimestampableTrait;
    use AdminstampableTrait;

    #[AssertValidator\Length(max: 255)]
    #[ORM\Column(type: Types::STRING, length: 255, nullable: false)]
    public string $title;

    #[AssertValidator\Length(min: 3, max: 3)]
    #[ORM\Column(type: Types::STRING, length: 3, nullable: true)]
    public string $ioc;

    #[AssertValidator\Length(min: 2, max: 2)]
    #[ORM\Column(type: Types::STRING, length: 2, nullable: true)]
    public string $iso;

    /**
     * One User have Many UsersObjects.
     * @see UsersObjects::$country
     * @var PersistentCollection|UsersObjects[]
     */
    #[ORM\OneToMany(targetEntity: UsersObjects::class, mappedBy: 'country')]
    #[ORM\OrderBy(['id' => 'DESC'])]
    public $users_objects;

    public function __construct()
    {
        parent::__construct();
    }

    public function __toString()
    {
        return '[#' . $this->id . '] ' . $this->title;
    }
}
