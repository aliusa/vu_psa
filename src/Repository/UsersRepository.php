<?php

namespace App\Repository;

use App\Entity\Users;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Lock\LockFactory;

/**
 * @method Users|null find($id, $lockMode = null, $lockVersion = null)
 * @method Users|null findOneBy(array $criteria, array $orderBy = null)
 * @method Users[]    findAll()
 * @method Users[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsersRepository extends BaseRepository
{
    private LockFactory $lockFactory;

    public function __construct(ManagerRegistry $registry, LockFactory $lockFactory)
    {
        parent::__construct($registry, Users::class);
        $this->lockFactory = $lockFactory;
    }

    public function findOneByEmail(string $email): ?Users
    {
        return $this->createQueryBuilder('u')
            ->where('u.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
