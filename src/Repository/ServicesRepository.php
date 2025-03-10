<?php

namespace App\Repository;

use App\Entity\Services;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends BaseRepository<Services>
 */
class ServicesRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Services::class);
    }

    //    /**
    //     * @return Services[] Returns an array of Services objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Services
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function getActiveServices(): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.active_from <= :now')
            ->andWhere('s.active_to IS NULL OR s.active_to < :now')
            ->andWhere('s.advertise = 1')
            ->setParameter('now', new \DateTime())
            ->orderBy('s.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
