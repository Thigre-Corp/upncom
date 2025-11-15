<?php
/*
            Service de gestion des tags
            reçoit un texte MarkUp 
            extrait les mots entre balises <strong></strong>
            compare à la liste existante
            -> indexe si existant
            -> crée et indexe si non
            retourne quelquechose
*/
namespace App\Service;

use Symfony\Component\DomCrawler\Crawler;
use App\Entity\Tag;

use Doctrine\ORM\EntityManagerInterface;
use Exception;

class TaggelService{
    
    public function __construct(
        private EntityManagerInterface $em,
    )
        {
        }

    public function taggelizator(?string $textToAnalyse, ?Object $toReceive)
    {
        if($textToAnalyse === null || $toReceive === null){
            throw new Exception("Une erreur est survenue !");
        }
        // récupérer les Tags déjà associés à l'objet passé (si existants)
        $toReceiveTags = $toReceive->getTags();
        // instancier un Crawler sur le texte à analyser
        $crawler = new Crawler($textToAnalyse, useHtml5Parser: true);
        // extraire du texte les éléments entre balises <strong> pour lister les tags
        $tags = $crawler
            ->filter('strong')
            ->each(function (Crawler $node, $i): string  // var $i nécessaire à each()
                {
                    //retourne le texte contenu entre les balises strong, mise en forme sans caratères unicode éronnés ( 'Â' et ' ' - caractère transparent.) et esapces en trop.
                    return preg_replace(
                        ['/\s\s+/', '/\xc2\xa0/'],  //[RegEx]
                        [' ', ''],      //[valeurs de remplacement]
                        strtolower($node->innerText()));
                });
        // virer les doublons, c'est çà de gagner sur la boucle.
        $tags = array_unique($tags);

        // contrôler les tags reçus un à un
        foreach ($tags as $tag) {
            // comparer si le tag existe en BDD 
            $persistedTag = $this->em->getRepository(Tag::class)->findOneByTagName($tag);
            
            // si le tag exsite en BDD , verifier qu'il n'est pas déjà lié à l'objet passé
            if ($persistedTag != null){
                // si aucun tag dans la collection, l'ajouter
                if ($toReceiveTags->isEmpty()){
                    $toReceive->addTag($persistedTag);
                }
                // si un ou plusieurs tags existe dans l'objet passé,
                else{
                    // verifier s'il ne fait pas déjà parti de la collection,
                    if( !($toReceiveTags->contains($persistedTag))){
                        //et l'y ajouter
                        $toReceive->addTag($persistedTag);
                    }
                }
            }
            // si le tag est absent de la BDD , l'y créé et l'ajouter à l'objet passé
            else{
                //créer le Tag en BDD -> sera flush() avec l'objet passé par EasyAdmin/Doctrine
                $newTag = new Tag;
                $newTag->setTagName($tag);
                $this->em->persist($newTag);
                //l'ajouter 
                $toReceive->addTag($newTag);
            }
            // mettre à jour la liste des tags associés - anti-doublette.
            $toReceiveTags = $toReceive->getTags();
        }
        return;
    }
}