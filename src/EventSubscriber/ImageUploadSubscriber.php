<?php 
// src/EventSubscriber/ImageUploadSubscriber.php

namespace App\EventSubscriber;

use App\Entity\Image;
use App\Service\ImageService; // Votre service d'upload
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ImageUploadSubscriber implements EventSubscriberInterface
{
    private ImageService $uploaderService;

    public function __construct(ImageService $uploaderService)
    {
        $this->uploaderService = $uploaderService;
    }

    public static function getSubscribedEvents(): array
    {
        // Écoute les événements avant la création et la mise à jour
        return [
            BeforeEntityPersistedEvent::class => ['uploadImage'],
            BeforeEntityUpdatedEvent::class => ['uploadImage'],
        ];
    }

    public function uploadImage($event): void
    {
        $entity = $event->getEntityInstance();

        //dd('subscriber');
        // Assurez-vous que l'entité traitée est bien celle avec le champ d'upload
        if (!($entity instanceof Image)) {
            return;
        }

        // 1. Vérifier si un nouveau fichier a été uploadé
        if ($entity->getImageFile() !== null) {
            // 2. Appeler votre service d'upload personnalisé
            $newFileName = $this->uploaderService->standardizator($entity->getImageFile(), $entity->getAltText());

            // 3. Mettre à jour la propriété persistée de l'entité
            $entity->setMediaURL($newFileName);
            
            // 4. (Optionnel) Vider le fichier temporaire après traitement
            $entity->setImageFile(null); 
        }

        // L'entité est maintenant prête à être persistée par EasyAdmin/Doctrine
    }
}