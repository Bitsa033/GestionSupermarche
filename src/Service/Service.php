<?php

namespace App\Service;

use App\Entity\Achat;
use App\Entity\CapaciteMagasin;
use App\Entity\Famille;
use App\Entity\Magasin;
use App\Entity\Margeprix;
use App\Entity\Produit;
use App\Entity\Reception;
use App\Repository\UvalRepository;
use App\Repository\AchatRepository;
use App\Entity\Stock;
use App\Entity\Uval;
use App\Repository\CapaciteMagasinRepository;
use App\Repository\FamilleRepository;
use App\Repository\MagasinRepository;
use App\Repository\MargeprixRepository;
use App\Repository\ProduitRepository;
use App\Repository\ReceptionRepository;
use App\Repository\StockRepository;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;

class Service{
    
    public $table_user;
    public $table_famille;
    public $table_produit;
    public $table_achat;
    public $table_reception;
    public $table_uval;
    public $table_margeprix;
    public $table_stock;
    public $table_profit;
    public $table_magasin;
    public $table_capacite_magasin;

    public $repo_user;
    public $repo_famille;
    public $repo_produit;
    public $repo_achat;
    public $repo_reception;
    public $repo_uval;
    public $repo_margeprix;
    public $repo_stockt;
    public $repo_profit;
    public $repo_magasin;
    public $repo_capacite_magasin;

    public $db;

    function __construct(
        FamilleRepository $familleRepository,
        ProduitRepository $produitRepository,
        AchatRepository $achatRepository,
        ReceptionRepository $receptionRepository,
        UvalRepository $uvalRepository,
        MargeprixRepository $margeprixRepository,
        StockRepository $stockRepository,
        ManagerRegistry $managerRegistry,
        MagasinRepository $magasinRepository,
        CapaciteMagasinRepository $capaciteMagasinRepository
    )
    {
        $this->repo_famille=$familleRepository;
        $this->repo_produit=$produitRepository;
        $this->repo_achat=$achatRepository;
        $this->repo_reception=$receptionRepository;
        $this->repo_uval=$uvalRepository;
        $this->repo_margeprix=$margeprixRepository;
        $this->repo_stockt=$stockRepository;
        $this->repo_magasin=$magasinRepository;
        $this->repo_capacite_magasin=$capaciteMagasinRepository;

        $this->table_famille= Famille::class;
        $this->table_produit= Produit::class;
        $this->table_achat= Achat::class;
        $this->table_reception= Reception::class;
        $this->table_uval= Uval::class;
        $this->table_margeprix= Margeprix::class;
        $this->table_stock= Stock::class;
        $this->table_magasin= Magasin::class;
        $this->table_capacite_magasin= CapaciteMagasin::class;

        $this->db=$managerRegistry->getManager();

    }

    public function insert_to_db($entity){
       
        $this->db->persist($entity);
        $this->db->flush();
    }


    public function multiple_row($array)
    {
        foreach ($array as $key => $value) {
            $k[] = $key;
            $v[] = $value;
        }
        $k = implode(",", $k);
        $v = implode(",", $v);

        return $array;
    }

    //on cree la methode qui permettra d'enregistrer les receptions du post dans la bd
         
    function new_reception($data)
    {

        $achat=$this->repo_achat->find($data['achat']);
        $magasin=$this->repo_magasin->find($data['magasin']);
        //on enregistre la reception d'achat(commande fournisseur) dans le stock entrant
        $reception = new $this->table_reception;
        $reception->setCommande($achat);
        $reception->setQte($data['quantite']);
        $reception->setPrixTotal($data['prixTotal']);
        $reception->setMagasin($magasin);
        $reception->setDateReception(new \DateTime());
        //on met à jour l'epace de stockge
        $cap_mag=$this->repo_capacite_magasin->find($magasin);
        $cap_init=$cap_mag->getCapaciteActuel();
        $cap_act= $cap_init - floatval($data['quantite']);
        $cap_mag->setCapaciteActuel($cap_act);
        $this->db->persist($cap_mag);
        $this->db->flush();
        
        //on enregistre sa valeur dans le stock sortant
        $stock= new Stock();
        $stock->setReception($reception);
        $stock->setQteInit($data['quantiteInitVal']);
        $stock->setQteTot($data['quantiteTotVal']);
        $stock->setPrixTotal($data['prixTotVal']);
        $stock->setDateStockage(new \DateTime());
        $this->insert_to_db($stock);

    }

    //on cree la methode qui permettra d'enregistrer les achats du post dans la bd
         
    function new_achat($data)
    {

        $produit=$this->repo_produit->find($data['produit']);
    
        $achat = new $this->table_achat;
        $achat->setProduit($produit);
        $achat->setQte($data['quantite']);
        $achat->setPrixATotal($data['prixTotal']);
        $achat->setDateachat(new \DateTime());
        // $stock->setRef(strtoupper($data['ref']));
        $this->insert_to_db($achat);
    }

    //on cree la methode qui permettra d'enregistrer les produits du post dans la bd
    function new_produit($data)
    {
        $this->multiple_row($data);
        
        $famille=$this->repo_famille->find($data['id_famille']);
        $unite_achat=$this->repo_uval->find($data['uniteAchat']);
        $unite_vente=$this->repo_uval->find($data['uniteVente']);

        $produit = new $this->table_produit;
        $produit->setFamille($famille);
        $produit->setNom(ucfirst($data['produit']));
        $produit->setCode(strtoupper($data['code']));
        $produit->setPrixAchat($data['prixAchat']);
        $produit->setPrixVente($data['prixVente']);
        $produit->setUniteAchat($unite_achat);
        $produit->setUniteVente($unite_vente);
        $this->insert_to_db($produit);
    }

    //on cree la methode qui permettra d'enregistrer les familles de produits du post dans la bd
    function new_famille($data)
    {
        $this->multiple_row($data);
       
        $famille = new $this->table_famille;
        // $famille->setUser($user);
        $famille->setNom(ucfirst($data['famille']));
        $this->insert_to_db($famille);
    }

    function new_uval($data)
    {
        $unite= new $this->table_uval;
        $unite->setNomuval($data);
        $this->insert_to_db($unite);
    }

    
}