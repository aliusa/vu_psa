<?php

namespace App\Traits;

use App\Entity\Administrators;
use Doctrine\ORM\Mapping as ORM;

trait AdminstampableTrait
{
    /**
     * This is the owning side.
     */
    #[ORM\ManyToOne(targetEntity: Administrators::class)]
    #[ORM\JoinColumn(name: 'admin_id', referencedColumnName: 'id', onDelete: 'RESTRICT')]
    public ?Administrators $admin = null;
}
