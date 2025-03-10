<?php

namespace App\Traits;

use Doctrine\ORM\Mapping as ORM;

trait TimestampableTrait
{
    /**
     * Sukūrimo laikas
     */
    #[ORM\Column(type: 'datetime_immutable', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    public ?\DateTimeImmutable $created_at = null;

    /**
     * Atnaujinimo laikas
     */
    #[ORM\Column(type: 'datetime_immutable', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    public ?\DateTimeImmutable $updated_at = null;

    public function touchForUpdate()
    {
        $this->updated_at = new \DateTimeImmutable();
    }

    #[ORM\PrePersist]
    public function setCreatedAt(): void
    {
        $this->created_at = new \DateTimeImmutable();
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function setUpdatedAt(): void
    {
        $this->updated_at = new \DateTimeImmutable();
    }
}
