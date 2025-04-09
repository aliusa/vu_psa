<?php

namespace App\Repository;

use App\Entity\Services;
use Doctrine\Persistence\ManagerRegistry;

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
    //        return $this->createQueryBuilder('service')
    //            ->andWhere('service.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('service.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Services
    //    {
    //        return $this->createQueryBuilder('service')
    //            ->andWhere('service.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function getActiveServices(): array
    {
        return $this->createQueryBuilder('service')
            ->andWhere('service.active_from <= :now')
            ->andWhere('service.active_to IS NULL OR service.active_to < :now')
            ->andWhere('service.advertise = 1')
            ->setParameter('now', new \DateTime())
            ->orderBy('service.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
