<?php
/*
            Service de gestion des images
            reçoit des fichiers images
            envoyer à: Subscriber
            contenu : Newsletter 
*/
namespace App\Service;


class ImageService{


    public function __construct(){

    }

    public function createImages(array $images): void {
    /*  -> récupérer les images
        -> on renomme les fichiers
        -> on les déplace vers le dossier public/img...
        -> on persiste

    */
    }
}