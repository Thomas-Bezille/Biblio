<?php

namespace App\EventSubscriber;

use App\Entity\Invitation;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['setUuid'],
        ];
    }

    public function setUuid(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if ($entity instanceof Invitation) {
            $entity->setUuid(Uuid::v4());
        }

        return;
    }
}