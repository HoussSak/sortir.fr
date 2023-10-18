<?php

namespace App\Repository;

use App\Entity\HistoStories;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HistoStories>
 *
 * @method HistoStories|null find($id, $lockMode = null, $lockVersion = null)
 * @method HistoStories|null findOneBy(array $criteria, array $orderBy = null)
 * @method HistoStories[]    findAll()
 * @method HistoStories[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HistoStoriesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HistoStories::class);
    }

//    /**
//     * @return HistoStories[] Returns an array of HistoStories objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('h.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?HistoStories
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
