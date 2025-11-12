<?php 
// src/EventSubscriber/ArticleTagSubscriber.php

namespace App\EventSubscriber;

use App\Entity\Article;
use App\Entity\User;

use Symfony\Bundle\SecurityBundle\Security;
use App\Service\ImageService; // service de TAG !!!
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;

class ArticleTagSubscriber implements EventSubscriberInterface
{

    public function __construct(
        private Security $security,
        )
    {
        
    }

    public static function getSubscribedEvents(): array
    {
        // Écoute les événements avant la création et la mise à jour
        return [
            BeforeEntityPersistedEvent::class => ['createOrModArticle'],
            BeforeEntityUpdatedEvent::class => ['createOrModArticle'],
        ];
    }

    public function createOrModArticle($event ): void
    {
        $entity = $event->getEntityInstance();

        // Assurez-vous que l'entité traitée est bien celle avec le champ d'upload
        if (!($entity instanceof Article)) {
            return;
        }

        // Si pas de date de création, en imposé une à now() et créé l'auteur
        if ($entity->getDateCreation() === null) {
            $entity->setDateCreation( new \dateTime() );
            $entity->setAuteur( CurrentUser::getUser()->getId());
        }
        // mettre à jour dateModification à now()
        $entity->setDateModification( new \dateTime() );
        $entity->setAuteur( $this->security->getUser());
        
            // 3. Mettre à jour la propriété persistée de l'entité
            
            
            // 4. (Optionnel) Vider le fichier temporaire après traitement
        //dd($entity);
    }


        // L'entité est maintenant prête à être persistée par EasyAdmin/Doctrine

}