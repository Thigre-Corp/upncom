<?php
/*
            Service de gestion des images
            reçoit des fichiers images
            envoyer à: Subscriber
            contenu : Newsletter 
*/
namespace App\Service;

use App\Entity\Image;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ImageService{

    public function __construct(
        private ParameterBagInterface $params , // récupérer les paramètres depuis ParametreBag (chemin Dossier upload)
        private EntityManagerInterface $entityManager ,
    ){

    }

    public function standardizator(UploadedFile $image, ?string $origine='', ?int $max_width = 1920 ): Image {
    /*  -> formater toutes uploader en webp
        -> limiter leur largeur à 1920px max (std full HD) par défaut
        -> pas de limite en hauteur (cas d'images scrollable)
        -> on renomme les fichiers
        -> on les déplace vers le dossier ad-hoc..
        -> on persiste et signe.
    */

        // créer chemin de stockage
        $path = $this->params->get('uploads_directory');

        //créer dossier si null avec droits
        if(!file_exists($path)){
            mkdir($path, 0755, true);
        }

        //donner un nom à l'image:
        $file = $origine.'-'.uniqid(); //ajouter webp ou svg en fin de course // prévoir modification du nom pour SEO.

        // si fichier vectoriel
        if($image->guessExtension() === 'svg'){ 
            $file = $file . '.svg';
            $image->move($path, $file);
        }
        // si fichier matriciel
        else{
            $file = $file . '.webp';
            //récuper les infos du fichie
            $imageInfos = getimagesize($image);
            
            if($imageInfos === false){
                throw new Exception('Format d\'image incorrect');
            }
            switch($imageInfos['mime']){
                case 'image/png':
                    $imageSource = imagecreatefrompng($image);
                    break;
                case 'image/jpeg':
                    $imageSource = imagecreatefromjpeg($image);
                    break;
                case 'image/webp':
                    $imageSource = imagecreatefromwebp($image);
                    break;
                default:
                    throw new Exception('Format d\'image incompatible: Seulement jpg, png, webp');
            }
            //récupérer les dimensions de l'image
            $imageSourceWidth = $imageDestWidth = $imageInfos[0];
            $imageSourceHeight = $imageDestHeight = $imageInfos[1];
            //si la largeur est supérieur à la max width autorisée, faire ratio height pour redimensionner.
            if($imageSourceWidth > $max_width){
                $imageDestWidth = $max_width;
                $imageDestHeight = $imageSourceHeight / $imageSourceWidth * $max_width;
            }
            // créer image vierge - GD Image
            $resizedImage = imagecreatetruecolor($imageDestWidth, $imageDestHeight);
            // générer contenu de l'image
            imagecopyresampled(
                $resizedImage,  // destination
                $imageSource,   // source
                0,              // décallage en X sur destination
                0,              // décallage en Y sur destination
                0,          // décallage en X depuis source
                0,          // décallage en Y depuis source
                $imageDestWidth,         // largeur destination
                $imageDestHeight,         // hauteur destination
                $imageSourceWidth,    // largeur source prélevée
                $imageSourceHeight     // hauteur source prélevée
            );

            //stocker et convertir en webP
            imagewebp($resizedImage, $path . $file);
        }

        $instanceImage = new Image();
        $instanceImage->setMediaURL($path.$file);
        $instanceImage->setAltText($origine);

        $this->entityManager->persist($instanceImage);
        $this->entityManager->flush($instanceImage);

        return $instanceImage;
    }
}