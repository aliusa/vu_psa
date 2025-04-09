<?php

namespace App\Repository;

use App\Entity\UsersObjectsServicesPromotions;
use Doctrine\Persistence\ManagerRegistry;

class UsersObjectsServicesPromotionsRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UsersObjectsServicesPromotions::class);
    }
}
