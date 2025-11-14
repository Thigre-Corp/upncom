<?php 
// src/EventSubscriber/ImageUploadSubscriber.php

namespace App\EventSubscriber;

use App\Entity\Image;
use App\Service\ImageService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityDeletedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;

class ImageUploadSubscriber implements EventSubscriberInterface
{
    private ImageService $uploaderService;

    public function __construct(ImageService $uploaderService)
    {
        $this->uploaderService = $uploaderService;
    }

    public static function getSubscribedEvents(): array
    {
        // Écouter les événements avant la création et la mise à jour
        return [
            BeforeEntityPersistedEvent::class => ['uploadImage'],
            BeforeEntityUpdatedEvent::class => ['uploadImage'],
            BeforeEntityDeletedEvent::class => ['cleanImage'],
        ];
    }

    public function uploadImage($event): void
    {
        $entity = $event->getEntityInstance();

        // si l'entité conncernée n'est pas de type Image, s'arrêter là.
        if (!($entity instanceof Image)) {
            return;
        }

        // Vérifier si un nouveau fichier a été uploadé
        if ($entity->getImageFile() !== null) {
            // faire appel au service d'uploaderService->standardizator() , avec en retour le nom de fichier.
            $newFileName = $this->uploaderService->standardizator($entity->getImageFile(), $entity->getAltText());

            // passer le nom du fichier dans l'attribut de l'entité
            $entity->setMediaURL($newFileName);
            
            // Vider le fichier temporaire après traitement
            $entity->setImageFile(null); 
        }
        return;
    }

    public function CleanImage($event): void
    {
        $entity = $event->getEntityInstance();

        // si l'entité conncernée n'est pas de type Image, s'arrêter là.
        if (!($entity instanceof Image)) {
            return;
        }
        // appel du service de suppression du fichier image
        $this->uploaderService->removator($entity->getMediaURL());
        return;
    }
    // persister au retour grâce à EasyAdmin/Doctrine
}