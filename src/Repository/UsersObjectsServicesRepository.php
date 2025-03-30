<?php

namespace App\Repository;

use App\Entity\UsersObjectsServices;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends BaseRepository<UsersObjectsServices>
 */
class UsersObjectsServicesRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UsersObjectsServices::class);
    }
}
