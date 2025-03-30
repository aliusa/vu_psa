<?php

namespace App\Entity;

use App\Repository\ServicesPromotionsRepository;
use App\Traits\AdminstampableTrait;
use App\Traits\IdTrait;
use App\Traits\TimestampableTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as AssertDoctrine;
use Symfony\Component\Validator\Constraints as AssertValidator;

#[ORM\Table('services_promotions')]
#[ORM\Entity(repositoryClass: ServicesPromotionsRepository::class)]
#[ORM\HasLifecycleCallbacks]
class ServicesPromotions extends BaseEntity
{
    use IdTrait;
    use TimestampableTrait;
    use AdminstampableTrait;

    /**
     * This is the owning side.
     * @see ServicesCategories::$services_promotions
     * @var \Proxies\__CG__\App\Entity\ServicesCategories|ServicesCategories
     */
    #[ORM\ManyToOne(targetEntity: ServicesCategories::class, inversedBy: 'services_promotions')]
    #[ORM\JoinColumn(name: 'services_categories_id', referencedColumnName: 'id', onDelete: 'RESTRICT')]
    public $services_categories;

    /**
     * @var int Kiek procentų nuolaida. Nuo 0.
     */
    #[AssertValidator\Range(min: 1, max: 100)]
    #[ORM\Column(type: Types::FLOAT, nullable: false, precision: 2)]
    public $discount = 1;

    /**
     * @var int Kiek mėnesių taikyti nuolaidą
     */
    #[AssertValidator\Range(min: 1, max: 12)]
    #[ORM\Column(type: Types::INTEGER, nullable: false)]
    public int $months = 1;

    public function __construct()
    {
        parent::__construct();
    }

    public function __toString(): string
    {
        return implode(' - ', ["[#{$this->getId()}]"]);
    }
}
