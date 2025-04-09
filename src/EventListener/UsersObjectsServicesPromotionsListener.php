<?php

namespace App\EventListener;

use App\Entity\UsersObjectsServicesPromotions;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;
use Symfony\Bundle\SecurityBundle\Security;

#[AsEntityListener(event: Events::prePersist, entity: UsersObjectsServicesPromotions::class)]
class UsersObjectsServicesPromotionsListener
{
    public function __construct(
        private Security $security,
    )
    {
    }

    public function prePersist(UsersObjectsServicesPromotions $usersObjectsServicesPromotions, PrePersistEventArgs $args)
    {
        $user = $this->security->getUser();
        if ($user) {
            $usersObjectsServicesPromotions->admin = $this->security->getUser();
        }
    }
}
