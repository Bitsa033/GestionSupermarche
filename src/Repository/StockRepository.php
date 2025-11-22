<?php

namespace App\Repository;

use App\Entity\Stock;
use App\Service\Db;
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
    public $db;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Stock::class);
        $this->db = new Db();
    }

    public function updateStock($id, $qte, $prix)
    {
        # code...
        $sql = " UPDATE `stock` SET `qte_tot`= qte_tot - $qte,
        stock.prix_total = stock.prix_total - $prix WHERE produit_id =
        (SELECT DISTINCT produit.id from reception INNER join 
        achat ON achat.id = reception.commande_id 
        INNER JOIN produit on produit.id = achat.produit_id WHERE achat.produit_id=" . $id . " )
            ";
        $this->db->insert_command($sql);
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
