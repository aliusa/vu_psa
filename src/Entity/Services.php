<?php

namespace App\Entity;

use App\Repository\ServicesRepository;
use App\Traits\AdminstampableTrait;
use App\Traits\IdTrait;
use App\Traits\TimestampableTrait;
use Doctrine\DBAL\Types\Types;
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
    use AdminstampableTrait;

    #[AssertValidator\Length(max: 255)]
    #[ORM\Column(type: Types::STRING, length: 255, nullable: false)]
    public $title;

    #[ORM\Column(type: Types::INTEGER, nullable: false)]
    #[AssertValidator\Range(min: 0, max: 9999)]
    public $price;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    public $description;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: false, options: ['default' => 'CURRENT_DATE'])]
    public \DateTime $active_from;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true, options: [])]
    public \DateTime $active_to;

    #[ORM\Column(type: Types::BOOLEAN, nullable: false, options: ['default' => false])]
    public $advertise;

    public function __construct()
    {
        parent::__construct();
        $this->active_from = new \DateTime();
    }

    public function __toString()
    {
        return implode(' - ', ["[#{$this->getId()}]", $this->title]);
    }
}
