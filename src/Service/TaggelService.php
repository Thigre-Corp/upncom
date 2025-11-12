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

use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;

class TaggelService{
    

    public function __construct(
        private EntityManagerInterface $em,
    )
        {
            
            //throw new \Exception('Not implemented');
        }

    public function taggelizator(string $textToAnalyse, Object $toReceive)
    {
        //dd($toReceive);
        $toReceiveTags = $toReceive->getTags();
        $crawler = new Crawler($textToAnalyse, useHtml5Parser: true);
        $tags = $crawler
            ->filter('strong')
            ->each(function (Crawler $node, $i): string 
                {
                    return $node->text();
                });
        foreach ($tags as $tag) {
            $persistedTag = $this->em->getRepository(Tag::class)->findOneByTagName($tag);
            if ($persistedTag !== null){
                // on ne rentre pas dans forEach. Pk ??? Voir collection
                // c'est NULL, il n'y a rien a itéré......
                dd($toReceiveTags);

                foreach($toReceiveTags as $toReceiveTag){
                    var_dump($toReceiveTags);
                    dd($toReceiveTag);
                    if ($persistedTag === $toReceiveTag){
                        echo ('y en a');
                    }
                    else {
                        $toReceive->addTag($persistedTag);
                        dd($toReceive);
                    }
                }
            }
            else 
                {
                echo($tag);
            }
        }
        //die;
    }
}