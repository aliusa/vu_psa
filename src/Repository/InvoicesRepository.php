<?php

namespace App\Repository;

use App\Entity\Invoices;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Invoices|null find($id, $lockMode = null, $lockVersion = null)
 * @method Invoices|null findOneBy(array $criteria, array $orderBy = null)
 * @method Invoices[]    findAll()
 * @method Invoices[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvoicesRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Invoices::class);
    }
}
