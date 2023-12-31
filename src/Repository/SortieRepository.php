<?php

namespace App\Repository;

use App\Entity\Sortie;
use App\utils\EtatEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Sortie $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Sortie $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Sortie[] Returns an array of Sortie objects
    //  */

     public function findByExampleField($site)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.site = :val')
            ->setParameter('val', $site)
            ->getQuery()
            ->getResult()

            ;
    }






    public function findOne($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.auteur = :user')
            ->setParameter('user', $value)
            ->getQuery()
            ->getResult()

        ;
    }

    public function findAllWithoutArchivee()
    {
        return $this->createQueryBuilder('s')
            ->where('s.etat != :archivedState')
            ->setParameter('archivedState', EtatEnum::ARCHIVEE)
            ->getQuery()
            ->getResult();
    }

}
