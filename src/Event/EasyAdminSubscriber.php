<?php

namespace App\Event;

use App\Entity\BaseEntity;
use App\Traits\AdminstampableTrait;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityUpdatedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeCrudActionEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    protected $usersObjectsStatusChanged = false;
    /** @var BaseSubscriber $subscriber */
    protected ?BaseSubscriber $subscriber = null;
    /** @var BaseEntity $entity */
    protected $entity;
    /** @var BaseEntity $entityChanged */
    protected $entityChanged;

    public function __construct(
        MailerInterface $mailer,
        private ContainerInterface $container,
        private Security $security
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        //https://symfony.com/bundles/EasyAdminBundle/master/events.html
        return [

            //Events related to Doctrine entities:

            //AfterEntityBuiltEvent::class => ['AfterEntityBuiltEvent'],

            //BeforeEntityDeletedEvent::class => ['BeforeEntityDeletedEvent'],
            //AfterEntityDeletedEvent::class => ['AfterEntityDeletedEvent'],


            /** @see BaseSubscriber::BeforeEntityUpdatedEvent() */
            BeforeEntityUpdatedEvent::class => ['BeforeEntityUpdatedEvent'],//on EDIT
            /** @see BaseSubscriber::AfterEntityUpdatedEvent() */
            AfterEntityUpdatedEvent::class => ['AfterEntityUpdatedEvent'],//on EDIT

            /** @see BaseSubscriber::BeforeEntityPersistedEvent() */
            BeforeEntityPersistedEvent::class => ['BeforeEntityPersistedEvent'],//on NEW
            /** @see BaseSubscriber::AfterEntityPersistedEvent() */
            AfterEntityPersistedEvent::class => ['AfterEntityPersistedEvent'],//on NEW



            //Events related to resource admins:

            BeforeCrudActionEvent::class => ['BeforeCrudActionEvent'],
            //AfterCrudActionEvent::class => ['AfterCrudActionEvent'],

        ];
    }


    //region doctrine events
    public function BeforeEntityUpdatedEvent(BeforeEntityUpdatedEvent $event)
    {
        //neišimti
        if (!$this->subscriber) {
            return;
        }

        if (method_exists($this->subscriber, __FUNCTION__)) {
            $this->subscriber->{__FUNCTION__}($this->entity, $event->getEntityInstance());
        }
    }

    public function AfterEntityUpdatedEvent(AfterEntityUpdatedEvent $event)
    {
        if (!$this->subscriber) {
            return;
        }

        $this->entityChanged = clone $event->getEntityInstance();
        $this->subscriber->entityAfter = clone $this->entityChanged;

        //todo 2021-09-15 12:15 alius.s: track if entity changed and what fields changed

        if (method_exists($this->subscriber, __FUNCTION__)) {
            $this->subscriber->{__FUNCTION__}($this->entity, $this->entityChanged);
        }
    }
    public function BeforeEntityPersistedEvent(BeforeEntityPersistedEvent $event)
    {
        $namespace = $this->getSubscriberNamespace($event->getEntityInstance());

        /** @see AdminstampableTrait::$admin */
        if (property_exists($event->getEntityInstance(), 'admin')) {
            $event->getEntityInstance()->admin = $this->security->getUser();
        }

        if (!$namespace) {
            return;
        }

        $this->subscriber = new $namespace($this->container);

        /** @see BaseSubscriber::BeforeEntityPersistedEvent() */
        if (method_exists($this->subscriber, __FUNCTION__)) {
            $this->subscriber->{__FUNCTION__}($event->getEntityInstance());
        }
    }
    public function AfterEntityPersistedEvent(AfterEntityPersistedEvent $event)
    {
        $this->entityChanged = $event->getEntityInstance();

        $namespace = $this->getSubscriberNamespace($this->entityChanged);

        if (!$namespace) {
            return;
        }

        $this->subscriber = new $namespace($this->container);

        /** @see BaseSubscriber::AfterEntityPersistedEvent() */
        if (method_exists($this->subscriber, __FUNCTION__)) {
            $this->subscriber->{__FUNCTION__}($this->entityChanged);
        }

    }
    //endregion doctrine events




    //region resource admins events
    public function BeforeCrudActionEvent(BeforeCrudActionEvent $event)
    {
        //Entity turi būti duombazėj
        if (null === $event->getAdminContext()->getEntity()->getInstance()) {
            return;
        }
        $this->entity = clone $event->getAdminContext()->getEntity()->getInstance();

        if ($namespace = $this->getSubscriberNamespace($this->entity)) {
            $this->subscriber = new $namespace($this->container);
            $this->subscriber->entityBefore = clone $this->entity;
            if (method_exists($this->subscriber, __FUNCTION__)) {
                $this->subscriber->{__FUNCTION__}($this->entity);
            }
        }
    }

    /*public function AfterCrudActionEvent(AfterCrudActionEvent $event)
    {
        /** @var EntityDto $entityDto */
    /*    $entityDto = $event->getResponseParameters()->get('entity');
        if ($entityDto && $entityDto->getInstance() instanceof UsersObjects) {
            //
        }
    }/**/
    //endregion resource admins events


    //region helpers
    protected function getSubscriberNamespace($entity): ?string
    {
        $className = (new \ReflectionClass($entity))->getShortName();

        $namespace = (new \ReflectionClass($this))->getNamespaceName();

        $subscriberNamespace = $namespace . "\\{$className}Subscriber";

        return class_exists($subscriberNamespace) ? $subscriberNamespace : null;
    }
    //endregion helpers
}
