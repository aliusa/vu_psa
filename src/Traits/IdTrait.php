<?php

namespace App\Traits;

use Doctrine\ORM\Mapping as ORM;

trait IdTrait
{
    #[ORM\Id()]
    #[ORM\GeneratedValue()]
    #[ORM\Column(type: 'bigint')]
    public ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }
}
