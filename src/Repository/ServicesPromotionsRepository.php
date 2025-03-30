<?php

namespace App\Repository;

use App\Entity\ServicesPromotions;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends BaseRepository<ServicesPromotions>
 * @method ServicesPromotions|null find($id, $lockMode = null, $lockVersion = null)
 * @method ServicesPromotions|null findOneBy(array $criteria, array $orderBy = null)
 * @method ServicesPromotions[]    findAll()
 * @method ServicesPromotions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServicesPromotionsRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ServicesPromotions::class);
    }
}
