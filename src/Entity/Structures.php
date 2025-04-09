<?php

namespace App\Entity;

use App\Repository\StructuresRepository;
use App\Traits\AdminstampableTrait;
use App\Traits\IdTrait;
use App\Traits\TimestampableTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as AssertDoctrine;
use Symfony\Component\Validator\Constraints as AssertValidator;

#[ORM\Table('structures')]
#[ORM\Entity(repositoryClass: StructuresRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Structures extends BaseEntity
{
    use IdTrait;
    use TimestampableTrait;
    use AdminstampableTrait;

    #[AssertValidator\Length(max: 255)]
    #[ORM\Column(type: Types::STRING, length: 255, nullable: false, unique: false)]
    public ?string $title = null;

    #[AssertValidator\Length(max: 255)]
    #[ORM\Column(type: Types::STRING, length: 255, nullable: false, unique: false)]
    public ?string $slug = null;

    #[AssertValidator\Length(max: 5000)]
    #[ORM\Column(type: Types::TEXT, length: 5000, nullable: false)]
    public ?string $description = null;

    #[ORM\Column(type: Types::BOOLEAN, nullable: false)]
    public $visible;

    public function __construct()
    {
        parent::__construct(...func_get_args());
    }

    public function __toString(): string
    {
        return implode(' - ', ["[#{$this->getId()}]", $this->title]);
    }
}
