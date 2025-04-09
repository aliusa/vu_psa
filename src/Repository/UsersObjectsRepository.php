<?php

namespace App\Repository;

use App\Entity\UsersObjects;
use Doctrine\Persistence\ManagerRegistry;

class UsersObjectsRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UsersObjects::class);
    }
}
