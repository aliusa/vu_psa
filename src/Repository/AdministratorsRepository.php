<?php

namespace App\Repository;

use App\Entity\Administrators;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @method Administrators|null find($id, $lockMode = null, $lockVersion = null)
 * @method Administrators|null findOneBy(array $criteria, array $orderBy = null)
 * @method Administrators[]    findAll()
 * @method Administrators[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdministratorsRepository extends BaseRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Administrators::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof Administrators) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }
}
