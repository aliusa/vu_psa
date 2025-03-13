<?php

namespace App\Traits;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

trait IdTrait
{
    #[ORM\Id()]
    #[ORM\GeneratedValue()]
    #[ORM\Column(type: Types::BIGINT)]
    public ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }
}
