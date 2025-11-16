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

    public function findBySearch( string $q ='*')
    {
        return $this->createQueryBuilder('Article')
            ->orderBy('Article.dateCreation', 'DESC') // on c
            ->Where('Article.estPublie = 1 ')
            ->andWhere('Article.titre LIKE :query')
            ->leftJoin('Article.tags', 't')
            ->orWhere('t.tagName LIKE :query')
            ->setParameter('query', '%'.$q.'%')
            ->getQuery()
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
