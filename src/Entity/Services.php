<?php

namespace App\Entity;

use App\Repository\ServicesRepository;
use App\Traits\IdTrait;
use App\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as AssertDoctrine;
use Symfony\Component\Validator\Constraints as AssertValidator;

#[ORM\Table('services')]
#[ORM\Entity(repositoryClass: ServicesRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Services extends BaseEntity
{
    use IdTrait;
    use TimestampableTrait;

    #[AssertValidator\Length(max: 255)]
    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    public $title;

    #[ORM\Column(type: 'integer', nullable: false)]
    #[AssertValidator\Range(min: 0, max: 9999)]
    public $price;

    #[ORM\Column(type: 'text', nullable: true)]
    public $description;

    #[ORM\Column(type: 'datetime_immutable', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    public $active_from;

    #[ORM\Column(type: 'datetime_immutable', nullable: true, options: ['default' => 'CURRENT_TIMESTAMP'])]
    public $active_to;

    #[ORM\Column(type: 'boolean', nullable: false, options: ['default' => false])]
    public $advertise;

    public function __toString()
    {
        return implode(' - ', ["[#{$this->getId()}]", $this->title]);
    }
}
