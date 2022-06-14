<?php

namespace App\Repository;

use App\Entity\Catuval;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Catuval|null find($id, $lockMode = null, $lockVersion = null)
 * @method Catuval|null findOneBy(array $criteria, array $orderBy = null)
 * @method Catuval[]    findAll()
 * @method Catuval[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CatuvalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Catuval::class);
    }

    // /**
    //  * @return Catuval[] Returns an array of Catuval objects
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
    public function findOneBySomeField($value): ?Catuval
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
