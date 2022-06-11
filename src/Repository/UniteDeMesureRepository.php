<?php

namespace App\Repository;

use App\Entity\UniteDeMesure;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UniteDeMesure|null find($id, $lockMode = null, $lockVersion = null)
 * @method UniteDeMesure|null findOneBy(array $criteria, array $orderBy = null)
 * @method UniteDeMesure[]    findAll()
 * @method UniteDeMesure[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UniteDeMesureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UniteDeMesure::class);
    }

    // /**
    //  * @return UniteDeMesure[] Returns an array of UniteDeMesure objects
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
    public function findOneBySomeField($value): ?UniteDeMesure
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
