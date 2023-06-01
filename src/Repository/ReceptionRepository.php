<?php

namespace App\Repository;

use App\Entity\Achat;
use App\Entity\Reception;
use App\Service\Db\Db;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

/**
 * @method Reception|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reception|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reception[]    findAll()
 * @method Reception[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReceptionRepository extends ServiceEntityRepository
{
    public $db;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reception::class);
        $this->db = new Db();
    }

    public function sommeQteRecue($commande)
    {
        $sql = '
        SELECT SUM(qte_rec) as qte FROM reception WHERE commande_id='.$commande;
        $stmt = $this->db->fetch_one_command($sql);
        //$qte=$stmt[0]['qte'];
        // returns an array of arrays (i.e. a raw data set)
        return $stmt['qte'] ;
        
    }

    public function qteUnitVal($commande)
    {
        $sql = '
        SELECT max(reception.id) as receptiion, qte_unit_val as qte FROM reception  
        WHERE commande_id='.$commande.'';
        $stmt = $this->db->fetch_one_command($sql);
        //dd($stmt);
        //$qte=$stmt[0]['qte'];
        // returns an array of arrays (i.e. a raw data set)
        return $stmt= (is_bool($stmt) == true) ? $stmt :$stmt['qte'] ;
        //return $stmt['qte'] ;
        
    }

    // /**
    //  * @return Reception[] Returns an array of Reception objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Reception
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
