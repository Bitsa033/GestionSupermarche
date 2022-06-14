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

    public function ListeProduitsSelonFifo()
    {
        //SELECT * FROM `stock` WHERE id=(SELECT max(id) FROM stock) AND qt>0
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
        
        SELECT produit.ref as reference,nomcatval as catUnite,nomuval as unite ,masse, produit.nom as nomProduit,famille.nom_fam as nomFamille,
        qt as qt, pvu as prixDeVente, pvt as prixTotalVente FROM produit inner join uval on 
        uval.id=produit.uvalp_id inner join catuval on catuval.id=uval.catuval_id inner join famille on famille.id=produit.famille_id LEFT join stock on stock.produit_id 
        =produit.id

        ';
        $stmt = $conn->prepare($sql);
        $stmt->executeQuery();

        // returns an array of arrays (i.e. a raw data set)
        return $stmt;
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

    /*
    public function findOneBySomeField($value): ?Produit
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
