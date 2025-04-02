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
     * @see Services::$services_promotions
     * @var \Proxies\__CG__\App\Entity\Services|Services
     */
    #[ORM\ManyToOne(targetEntity: Services::class, cascade: [], inversedBy: 'services_promotions')]
    #[ORM\JoinColumn(name: 'services_id', referencedColumnName: 'id', onDelete: 'RESTRICT')]
    public $services;

    /**
     * @var float Kiek procentų nuolaida. Nuo 0.
     */
    #[AssertValidator\Range(min: 0, max: 100)]
    #[ORM\Column(type: Types::FLOAT, nullable: false, precision: 2, options: ['default' => 0])]
    public float $discount = 0;

    /**
     * @var int Kiek mėnesių taikyti nuolaidą
     */
    #[AssertValidator\Range(min: 1, max: 12)]
    #[ORM\Column(type: Types::INTEGER, nullable: false)]
    public int $months = 1;

    /**
     * Imtinai
     */
    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: false, options: ['default' => 'CURRENT_DATE'])]
    public \DateTime $active_from;

    /**
     * Imtinai
     */
    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: false, options: [])]
    public \DateTime $active_to;

    public function __construct()
    {
        parent::__construct();
        if (!isset($this->active_from)) {
            $this->active_from = new \DateTime();
            $this->active_to = (new \DateTime())->add(new \DateInterval('P1Y'))->sub(new \DateInterval('P1D'));
        }
    }

    public function __toString(): string
    {
        return implode(' - ', ["[#{$this->getId()}]", $this->services->title, $this->discount.' %']);
    }
}
