<?php

namespace App\Repository;

use App\Entity\Uval;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Uval|null find($id, $lockMode = null, $lockVersion = null)
 * @method Uval|null findOneBy(array $criteria, array $orderBy = null)
 * @method Uval[]    findAll()
 * @method Uval[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UvalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Uval::class);
    }

    // /**
    //  * @return Uval[] Returns an array of Uval objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Uval
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
