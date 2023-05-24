<?php

namespace App\Repository;

use App\Entity\Produit;
use App\Service\Db\Db;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use PDO;

/**
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public $db;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
        $this->db=new Db();
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
        $sql = '
        SELECT produit.id as id, produit.nom as produit,
        prix_vente, SUM(qte_tot) as qte_en_stock, nomuval as emballage 
        FROM stock inner join reception on reception.id = stock.reception_id inner
        join achat on achat.id = reception.commande_id inner join produit on produit.id =
        achat.produit_id inner join uval on uval.id = produit.unite_vente_id  group by produit.id
        ;
        ';
        $rray=$this->db->fetch_all_command($sql);
        return $rray;
    }

    public function NbStockProduit()
    {
        $sql = '
        SELECT count(stock.id) as nbStock FROM stock INNER JOIN reception ON
        reception.id = stock.reception_id INNER JOIN achat ON
        achat.id = reception.commande_id INNER JOIN produit ON
        produit.id = achat.produit_id WHERE produit.id=1
        ';
        $array=$this->db->fetch_one_command($sql);
        $nb=$array['nbStock'];
        return $nb;
    }

    public function stockProduit()
    {
        $nb_stock=$this->NbStockProduit();
        for ($i=1; $i <=$nb_stock ; $i++) { 
            echo 'merci <br>';
        }
        // $sql = '
        // SELECT produit.nom,stock.qte_tot FROM stock INNER JOIN reception ON
        // reception.id = stock.reception_id INNER JOIN achat ON
        // achat.id = reception.commande_id INNER JOIN produit ON
        // produit.id = achat.produit_id
        // ;
        // ';
        // $rray=$this->db->new_fetch_command($sql);
        // return $rray;
    }
    
}
