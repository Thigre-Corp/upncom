<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    // public function findBySearch( string $q ='*')
    // {
    //     return $this->createQueryBuilder('Article')
    //         ->distinct('Article')
    //         ->orderBy('Article.dateCreation', 'DESC') // on c
    //         ->Where('Article.estPublie = 1 ')
    //         ->andWhere('Article.titre LIKE :query')
    //         ->leftJoin('Article.tags', 't')
    //         ->orWhere('t.tagName LIKE :query')
    //         ->setParameter('query', '%'.$q.'%')
    //         ->getQuery()
    //         ;
    // }

    public function findBySearchQb(string $query) //: array
{
    // On normalise et nettoie la query
    $query = trim(strtolower($query));
    $items = explode(' ', $query,'5');
    $qb = $this->createQueryBuilder('a')
        //
        ->leftJoin('a.tags', 't')
        ->distinct()
        ->where('a.estPublie = 1') // On affiche uniquement les articles publiés
        ->select('partial a.{id , dateCreation, titre, contenu}')
        ;
    // Si l'utilisateur a tapé quelque chose, on filtre
    if ($query !== '') {
        $items = explode(' ', $query,'5');
        
        foreach($items as $item){
            static $i=0;
            $qb->andWhere('
                LOWER(a.titre) LIKE :q'.$i.'
                OR LOWER(a.contenu) LIKE :q'.$i.'
                OR LOWER(t.tagName) LIKE :q'.$i.'
            ')
            ->setParameter('q'.$i, '%'.$item.'%');
            $i++;
        }
    }
    //dd($qb->getQuery()->getResult());

    // Tri final
    return $qb
        ->orderBy('a.dateCreation', 'DESC')
        //->getQuery()
        //->getResult()
        ;
}

    //    public function findOneBySomeField($value): ?Article
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
