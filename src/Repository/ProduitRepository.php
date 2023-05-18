<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    // /**
    //  * @return Produit[] Returns an array of Produit objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    
    public function stockTotal()
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
        SELECT produit.id as id, produit.nom as produit, prix_vente, SUM(qte_tot) as qte_en_stock, nomuval  FROM stock inner join reception on reception.id =
        stock.reception_id inner join achat on achat.id = reception.commande_id inner join produit on
        produit.id = achat.produit_id inner join uval on uval.id = produit.unite_vente_id group by produit.id
        ;
        ';
        $stmt = $conn->prepare($sql);
        $stmt->executeQuery();
        $arr=$stmt->fetchAll();
        return $arr;
    }
    
}
