<?php 
// src/EventSubscriber/ServiceTagSubscriber.php

namespace App\EventSubscriber;

use App\Entity\Service;

use App\Service\TaggelService; // service de TAG !!!
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;

class ServiceTagSubscriber implements EventSubscriberInterface
{

    public function __construct(
        private TaggelService $taggle,
        )
    {
    }

    public static function getSubscribedEvents(): array
    {
        // Écouter les événements avant la création et la mise à jour
        return [
            BeforeEntityPersistedEvent::class => ['createOrModService'],
            BeforeEntityUpdatedEvent::class => ['createOrModService'],
        ];
    }

    public function createOrModService($event ): void
    {
        $entity = $event->getEntityInstance();

        // si l'entité conncernée n'est pas de type Service, s'arrêter là.
        if (!($entity instanceof Service)) {
            return;
        }
        // faire appel au service taggel pour création/indexation des TAGS.
        $this->taggle->taggelizator($entity->getContenu(), $entity);
        return;
    }
    // persister au retour grâce à EasyAdmin/Doctrine
}