<?php

namespace App\Repository;

use App\Entity\Structures;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Structures|null find($id, $lockMode = null, $lockVersion = null)
 * @method Structures|null findOneBy(array $criteria, array $orderBy = null)
 * @method Structures[]    findAll()
 * @method Structures[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StructuresRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Structures::class);
    }
}
