<?php

namespace App\Repository;

use App\Entity\Stock;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Stock|null find($id, $lockMode = null, $lockVersion = null)
 * @method Stock|null findOneBy(array $criteria, array $orderBy = null)
 * @method Stock[]    findAll()
 * @method Stock[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StockRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Stock::class);
    }

    public function ListeStocksSelonFifo()
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
            select * from stock
        ';
        $stmt = $conn->prepare($sql);
        $stmt->executeQuery();

        // returns an array of arrays (i.e. a raw data set)
        return $stmt;
    }

    public function ValorisationStocks()
    {
        //SELECT * FROM `stock` WHERE id=(SELECT max(id) FROM stock) AND qt>0
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
            select * from stock
        ';
        $stmt = $conn->prepare($sql);
        $stmt->executeQuery();

        // returns an array of arrays (i.e. a raw data set)
        return $stmt;
    }

    // /**
    //  * @return Stock[] Returns an array of Stock objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Stock
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
