<?php

namespace App\Event;

use App\Entity\BaseEntity;
use Psr\Container\ContainerInterface;

abstract class BaseSubscriber
{
    /** @var BaseEntity $entityBefore */
    public $entityBefore = null;
    /** @var BaseEntity $entityAfterChange */
    public $entityAfter = null;

    protected ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }


    //region doctrine events
    /**
     * On EDIT. After entity updated.
     * @param BaseEntity $entityBefore
     * @param BaseEntity $entityAfter
     * @return void
     */
    abstract public function AfterEntityUpdatedEvent($entityBefore, $entityAfter);

    /**
     * On NEW. Before entity created.
     * @param BaseEntity $entity
     * @return void
     */
    //abstract public function BeforeEntityPersistedEvent($entity);

    /**
     * On NEW. After entity created.
     * @param BaseEntity $entity
     * @return void
     */
    abstract public function AfterEntityPersistedEvent($entity);
    //endregion doctrine events



    //region resource admins events
    /**
     * on INDEX, DETAIL, EDIT, NEW, DELETED, BATCHDELETE
     * @param BaseEntity $entityBefore
     * @return void
     */
    //abstract public function BeforeCrudActionEvent($entityBefore);
    /**
     * on INDEX, DETAIL, EDIT, NEW, DELETED, BATCHDELETE
     * @param BaseEntity $entityBefore
     * @return void
     */
    //abstract public function AfterCrudActionEvent($entityBefore);
    //endregion resource admins events

}
