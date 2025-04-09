<?php

namespace App\Repository;

use App\Entity\UsersObjectsServicesBundles;
use Doctrine\Persistence\ManagerRegistry;

class UsersObjectsServicesBundlesRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UsersObjectsServicesBundles::class);
    }

    //    /**
    //     * @return UsersObjectsServicesBundles[] Returns an array of UsersObjectsServicesBundles objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?UsersObjectsServicesBundles
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
