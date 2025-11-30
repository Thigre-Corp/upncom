<?php
/*
            Service de gestion des images
            reçoit des fichiers images
            standardise les JPEG/PNG en webp
            passthrough pour le SVG (sanitization à prévoir)
            retourne le nom du fichier
*/
namespace App\Service;

use Exception;
use enshrined\svgSanitize\Sanitizer;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ImageService{

    public function __construct(
        private ParameterBagInterface $params , // récupérer les paramètres depuis ParametreBag (chemin Dossier upload)
        private SluggerInterface $slugger,
    ){
    }

    public function standardizator(UploadedFile $image, ?string $origine='aRenommer', ?int $max_width = 1920 ): string // was Image
    {
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
        //génére la première partie du nom à partir du texte alternatif, limité à 30 caractères.
        $origine = substr($this->slugger->slug($origine),0,30);
        //rendre unique le nom de l'image:
        $file = $origine.'-'.uniqid();

        //vérifier le type de fichier uploadé en cas de by-pass de la contrainte sur l'entité
        if(
            $image->guessExtension() !== 'svg' &&
            $image->guessExtension() !== 'webp' &&
            $image->guessExtension() !== 'png' &&
            $image->guessExtension() !== 'jpg' &&
            $image->guessExtension() !== 'jpeg'
        )
        {
            throw new Exception('Format d\'image incorrect, ne pas tenter d\'uploder ce fichier à nouveau: ');
        }

        // si fichier vectoriel
        if($image->guessExtension() === 'svg'){ 
            $file= $file.".svg";
            // instancier un objet Sanitizer et le paramètrer: pas de lien externe, miniaturiser
            $svgSanitizer = new Sanitizer;
            $svgSanitizer->removeRemoteReferences(true);
            $svgSanitizer->minify(true);
            //récupérer le contenu du SVG sous forme de string
            $dirtySVG = file_get_contents($image);
            // Sanitizer le svg            
            $cleanSVG = $svgSanitizer->sanitize($dirtySVG);
            //convertir le SVG de string à fichier
            file_put_contents($path.$file, $cleanSVG);
        }
        // si fichier matriciel
        else{
            $file=$file.".webp";
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
                    throw new Exception('Une erreur est survenue');
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
                0,              // décallage en X depuis source
                0,              // décallage en Y depuis source
                $imageDestWidth,         // largeur destination
                $imageDestHeight,         // hauteur destination
                $imageSourceWidth,    // largeur source prélevée
                $imageSourceHeight     // hauteur source prélevée
            );

            //stocker et convertir en webP
            imagewebp($resizedImage, $path . $file);
        }
        return $file; 
    }

    public function removator(string $filenameToDelete): void
    {
        $path = $this->params->get('uploads_directory');
        $filesystem = new Filesystem();
        if($filesystem->exists($path.$filenameToDelete)){
            $filesystem->remove($path.$filenameToDelete);
        }
        return;
    }
}