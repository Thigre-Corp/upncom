<?php 
// src/EventSubscriber/ArticleTagSubscriber.php

namespace App\EventSubscriber;

use App\Entity\Article;
use App\Entity\User;

use Symfony\Bundle\SecurityBundle\Security;
use App\Service\TaggelService; // service de TAG !!!
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;

class ArticleTagSubscriber implements EventSubscriberInterface
{

    public function __construct(
        private Security $security,
        private TaggelService $taggle,
        )
    {
    }

    public static function getSubscribedEvents(): array
    {
        // Écouter les événements avant la création et la mise à jour
        return [
            BeforeEntityPersistedEvent::class => ['createOrModArticle'],
            BeforeEntityUpdatedEvent::class => ['createOrModArticle'],
        ];
    }

    public function createOrModArticle($event ): void
    {
        $entity = $event->getEntityInstance();

        // si l'entité conncernée n'est pas de type Article, s'arrêter là.
        if (!($entity instanceof Article)) {
            return;
        }

        // Si pas de date de création, en imposer une à now() et créé l'auteur (User actuel)
        if ($entity->getDateCreation() === null) {
            $entity->setDateCreation( new \dateTime() );
            $entity->setAuteur( $this->security->getUser());
        }
        // mettre à jour dateModification à now()
        $entity->setDateModification( new \dateTime() );
        
        // faire appel au service taggel pour création/indexation des TAGS.
        $this->taggle->taggelizator($entity->getContenu(), $entity);
    }
    // persister au retour grâce à EasyAdmin/Doctrine
}