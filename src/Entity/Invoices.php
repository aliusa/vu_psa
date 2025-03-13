<?php

namespace App\Entity;

use App\Traits\IdTrait;
use App\Traits\TimestampableTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\Validator\Constraints AS Assert;

class Invoices extends BaseEntity
{
    use IdTrait;
    use TimestampableTrait;

    /**
     * This is the owning side.
     * @see UsersObjectsServicesBundles::$
     * @var PersistentCollection|Services
     */
    #[ORM\ManyToOne(targetEntity: UsersObjectsServicesBundles::class, inversedBy: 'users_objects_services')]
    #[ORM\JoinColumn(name: 'users_objects_services_bundles_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    public $users_objects_services_bundles;

    #[Assert\Range(min: 0, max: 10000)]
    #[ORM\Column(type: Types::INTEGER, nullable: false)]
    public int $amount;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true, options: [])]
    public $due_date;

    #[ORM\Column(type: Types::BOOLEAN, nullable: false, options: ['default' => false])]
    public $is_paid;

    public function __toString()
    {
        return implode(' - ', ["[#{$this->getId()}]", ]);
    }
}
