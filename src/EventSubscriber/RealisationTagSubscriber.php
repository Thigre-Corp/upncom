<?php 
// src/EventSubscriber/RealisationTagSubscriber.php

namespace App\EventSubscriber;

use App\Entity\Realisation;

use App\Service\TaggelService; // Reservation de TAG !!!
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;

class RealisationTagSubscriber implements EventSubscriberInterface
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
            BeforeEntityPersistedEvent::class => ['createOrModRealisation'],
            BeforeEntityUpdatedEvent::class => ['createOrModRealisation'],
        ];
    }

    public function createOrModRealisation($event ): void
    {
        $entity = $event->getEntityInstance();
        // si l'entité conncernée n'est pas de type Realisation, s'arrêter là.
        if (!($entity instanceof Realisation)) {
            return;
        }
        // faire appel au Service taggel pour création/indexation des TAGS.
        $this->taggle->taggelizator($entity->getResumeMission(), $entity);
        if($entity->getDescription() != null)
        {
            $this->taggle->taggelizator($entity->getDescription(), $entity);
        }
        return;
    }
    // persister au retour grâce à EasyAdmin/Doctrine
}