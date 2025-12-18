<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 */
class ArticleRepository extends ServiceEntityRepository
{

    public function __construct(
        ManagerRegistry $registry,
        )
    {
        parent::__construct($registry, Article::class);
    }

    public function findBySearchQb(string $query): QueryBuilder
    {
        // posé la base de la reqûete DQL.
        $qb = $this->createQueryBuilder('a')
            //
            ->leftJoin('a.tags', 't')
            ->distinct()
            ->where('a.estPublie = 1') // afficher uniquement les articles publiés
            ;
        // Si l'utilisateur a tapé quelque chose, normalisé , mettre sous tableau chaque terme dans la limite de 5
        if ($query !== '') {
            $query = trim(strtolower($query));
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
        // Tri final
        return $qb
            ->orderBy('a.dateCreation', 'DESC')
            ;
    }

    public function findLastArticles(int $number = 3): array
    {
        $qb = $this->createQueryBuilder('a')
            ->distinct()
            ->where('a.estPublie = 1')
            ->orderBy('a.dateCreation', 'DESC')
            ->setMaxResults($number)
            ->getQuery();
        return $qb->getResult() ?? [];
    }
}
