<?php

declare(strict_types=1);

namespace Jeandaviddaviet\WebhookBundle\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Jeandaviddaviet\WebhookBundle\Services\WebhookNotifier;

class WebhookDoctrineOnFlushListener implements EventSubscriber
{
    public function __construct(
        private readonly WebhookNotifier $webhookNotifier,
    )
    {
    }

    public function getSubscribedEvents()
    {
        return [
            Events::onFlush
        ];
    }

    /*
     * TODO defer the actual message sending after the postFlush event by storing the
     * user from the onFlush to a property and then accessing it from the postFlush
     * event
     */

    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getObjectManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            $this->notify($entity, 'created');
        }

        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            $this->notify($entity, 'updated');
        }

        foreach ($uow->getScheduledEntityDeletions() as $entity) {
            $this->notify($entity, 'deleted');
        }

//        foreach ($uow->getScheduledCollectionDeletions() as $col) {
//            dd($col);
//        }
//
//        foreach ($uow->getScheduledCollectionUpdates() as $col) {
//            $this->notify($entity, 'deleted');
//        }
    }

    public function notify(mixed $entity, string $eventName)
    {
        $this->webhookNotifier->notify($entity, $eventName);
    }
}
