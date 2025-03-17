<?php

namespace App\Repository;

use App\Entity\Countries;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends BaseRepository<Countries>
 */
class CountriesRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Countries::class);
    }

    //    /**
    //     * @return Countries[] Returns an array of Countries objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('countries')
    //            ->andWhere('countries.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('countries.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Countries
    //    {
    //        return $this->createQueryBuilder('countries')
    //            ->andWhere('countries.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
