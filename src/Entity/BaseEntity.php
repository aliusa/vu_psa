<?php

namespace App\Entity;

use Doctrine\ORM\EntityManagerInterface;

abstract class BaseEntity
{
    public function __construct()
    {
        //
    }

    public static function getRepository(EntityManagerInterface $em)
    {
        return $em->getRepository(__CLASS__);
    }

    public function getId(): ?int
    {
        return $this->id ?? null;
    }
}
