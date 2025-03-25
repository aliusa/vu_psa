<?php

namespace App\Repository;

use App\Entity\Payments;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Lock\LockFactory;

/**
 * @extends BaseRepository<Payments>
 * @method Payments|null find($id, $lockMode = null, $lockVersion = null)
 * @method Payments|null findOneBy(array $criteria, array $orderBy = null)
 * @method Payments[]    findAll()
 * @method Payments[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaymentsRepository extends BaseRepository
{
    private LockFactory $lockFactory;

    public function __construct(ManagerRegistry $registry, LockFactory $lockFactory)
    {
        parent::__construct($registry, Payments::class);
        $this->lockFactory = $lockFactory;
    }
}
