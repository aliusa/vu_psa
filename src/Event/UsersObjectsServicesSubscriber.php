<?php

namespace App\Event;

use App\Entity\UsersObjectsServices;

class UsersObjectsServicesSubscriber extends BaseSubscriber
{
    public function AfterEntityUpdatedEvent($entityBefore, $entityAfter)
    {
        // TODO: Implement AfterEntityUpdatedEvent() method.
    }

    public function AfterEntityPersistedEvent($entity)
    {
        // TODO: Implement AfterEntityPersistedEvent() method.
    }

    public function BeforeEntityUpdatedEvent(UsersObjectsServices $entityBefore, UsersObjectsServices $entityAfter){
        //$entityAfter->total_price = $entityAfter->items_total * $entityAfter->unit_price;
    }

    /**
     * @param UsersObjectsServices $entity
     */
    public function BeforeEntityPersistedEvent($entity)
    {
        //$entity->total_price = $entity->items_total * $entity->unit_price;
    }
}
