<?php

namespace App\Repository;

use App\Entity\Config;
use Doctrine\Persistence\ManagerRegistry;

class ConfigRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Config::class);
    }
}
