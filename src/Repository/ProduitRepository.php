<?php

namespace App\Repository;

use App\Entity\Produit;
use App\Service\Db;
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

        $this->db = new Db();
    }

    public function prod_qty_price($id)
    {
        $sql1 = 'SELECT MAX(reception.id) as reception_id,reception.commande_id as commande_id
        , produit.id as produit_id, qte_tot_val,prix_tot_val  FROM reception inner join
         achat on achat.id = reception.commande_id INNER JOIN produit on produit.id = 
         achat.produit_id WHERE reception.commande_id ="' . $id . '" AND reception.id=(SELECT MAX(reception.id) 
         FROM reception WHERE reception.commande_id="' . $id . '")';
        $array = $this->db->fetch_one_command($sql1);
        $reception_id = $array['reception_id'];
        $commande_id = $array['commande_id'];
        $produit_id = $array['produit_id'];
        $qte_tot_val = $array['qte_tot_val'];
        $prix_total_val = $array['prix_tot_val'];

        $data = array(
            'reception_id' => $reception_id,
            'commande_id' => $commande_id,
            'produit_id'  => $produit_id,
            'qte_tot_val' => $qte_tot_val,
            'prix_total_val' => $prix_total_val

        );

        return $data;
    }

    public function identity_prod($id)
    {
        $sql1 = 'SELECT produit.id as id from produit
        INNER JOIN stock on stock.produit_id=produit.id
        WHERE stock.produit_id =' . $id;
        $array = $this->db->fetch_one_command($sql1);
        $produit_id = (is_bool($array) == true) ? $array : $array['id'];

        $data = array(
            'produit_id'  => $produit_id,
        );

        return $data;
    }

    public function nouveau_stock($id)
    {
        $bilanp = $this->prod_qty_price($id);
        $produit_id = $bilanp['produit_id'];
        $qte_tot_val = $bilanp['qte_tot_val'];
        $prix_total_val = $bilanp['prix_total_val'];
        $afficherp = $this->identity_prod($produit_id);
        $produit = $afficherp['produit_id'];

        if ($produit) {
            $sql = " UPDATE `stock` SET `qte_tot`= qte_tot + $qte_tot_val,
            stock.prix_total = stock.prix_total + $prix_total_val WHERE produit_id =
            " . $produit_id;
            $this->db->insert_command($sql);
        } else {
            $sql = " INSERT INTO `stock`(`produit_id`, `qte_tot`, `prix_total`) VALUES 
            ('$produit_id','$qte_tot_val','$prix_total_val') ";
            $this->db->insert_command($sql);
        }
    }
}
