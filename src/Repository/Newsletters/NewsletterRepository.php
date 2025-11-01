<?php

namespace App\Repository\Newsletters;

use App\Entity\Newsletters\Newsletter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Newsletter>
 */
class NewsletterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Newsletter::class);
    }

    /**
    * @return Newsletter[] Returns an array of Newsletter objects
    */
    public function findToBeSentToday(): array
    {
        $todayAm = new \DateTime('today');
        $todayPm = new \DateTime('tomorrow - 1 ms');

        return $this->createQueryBuilder('n')
            ->andWhere('n.isSent = false')
            ->andWhere('n.datePublication BETWEEN :from AND :to')
            ->setParameter('from', $todayAm )
            ->setParameter('to', $todayPm)
            ->orderBy('n.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    //    public function findOneBySomeField($value): ?Newsletter
    //    {
    //        return $this->createQueryBuilder('n')
    //            ->andWhere('n.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
