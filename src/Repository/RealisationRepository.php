<?php

namespace App\Repository;

use App\Entity\Realisation;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Realisation>
 */
class RealisationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Realisation::class);
    }

    public function findBySearchQb(string $query): QueryBuilder
    {
        //  normaliser et nettoyer la query
        $query = trim(strtolower($query));
        // 
        $items = explode(' ', $query,'5');
        $qb = $this->createQueryBuilder('r')
            //
            ->leftJoin('r.tags', 't')
            ->distinct()
            //->where('a.estPublie = 1') // afficher uniquement les realisations publiés
            ;
        // Si l'utilisateur a tapé quelque chose, filter
        if ($query !== '') {
            $items = explode(' ', $query,'5');
            
            foreach($items as $item){
                static $i=0;
                $qb->andWhere('
                    LOWER(r.accroche) LIKE :q'.$i.'
                    OR LOWER(r.description) LIKE :q'.$i.'
                    OR LOWER(r.resumeMission) LIKE :q'.$i.'
                    OR LOWER(t.tagName) LIKE :q'.$i.'
                ')
                ->setParameter('q'.$i, '%'.$item.'%');
                $i++;
            }
        }
        // Tri final
        return $qb
            // ->orderBy('a.dateCreation', 'DESC')
            ;
    }

    public function findLastRealisations(int $number = 3): array
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
