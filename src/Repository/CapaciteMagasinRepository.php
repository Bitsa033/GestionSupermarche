<?php

namespace App\Repository;

use App\Entity\CapaciteMagasin;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CapaciteMagasin|null find($id, $lockMode = null, $lockVersion = null)
 * @method CapaciteMagasin|null findOneBy(array $criteria, array $orderBy = null)
 * @method CapaciteMagasin[]    findAll()
 * @method CapaciteMagasin[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CapaciteMagasinRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CapaciteMagasin::class);
    }

    // /**
    //  * @return CapaciteMagasin[] Returns an array of CapaciteMagasin objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CapaciteMagasin
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
