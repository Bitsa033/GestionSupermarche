<?php

namespace App\Service;

use App\Entity\Achat;
use App\Entity\CapaciteMagasin;
use App\Entity\Categorie;
use App\Entity\Famille;
use App\Entity\Magasin;
use App\Entity\Margeprix;
use App\Entity\Produit;
use App\Entity\Reception;
use App\Entity\SortieStock;
use App\Repository\UvalRepository;
use App\Repository\AchatRepository;
use App\Entity\Stock;
use App\Entity\Uval;
use App\Repository\CapaciteMagasinRepository;
use App\Repository\CategorieRepository;
use App\Repository\FamilleRepository;
use App\Repository\MagasinRepository;
use App\Repository\MargeprixRepository;
use App\Repository\ProduitRepository;
use App\Repository\ReceptionRepository;
use App\Repository\SortieStockRepository;
use App\Repository\StockRepository;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;

class Service
{

    public $table_user;
    public $table_categorie;

    public $table_famille;
    public $table_produit;
    public $table_achat;
    public $table_reception;
    public $table_uval;
    public $table_margeprix;
    public $table_stock;
    public $table_sortiestock;
    public $table_magasin;
    public $table_capacite_magasin;

    public $repo_user;
    public $repo_famille;
    public $repo_produit;
    public $repo_achat;
    public $repo_reception;
    public $repo_uval;
    public $repo_margeprix;
    public $repo_stock;
    public $repo_sortiestock;
    public $repo_magasin;
    public $repo_capacite_magasin;
    public $repo_categorie;

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
        SortieStockRepository $sortieStockRepository,
        CapaciteMagasinRepository $capaciteMagasinRepository,
        CategorieRepository $categorieRepository
    ) {
        $this->repo_famille = $familleRepository;
        $this->repo_produit = $produitRepository;
        $this->repo_achat = $achatRepository;
        $this->repo_reception = $receptionRepository;
        $this->repo_uval = $uvalRepository;
        $this->repo_margeprix = $margeprixRepository;
        $this->repo_stock = $stockRepository;
        $this->repo_magasin = $magasinRepository;
        $this->repo_sortiestock = $sortieStockRepository;
        $this->repo_capacite_magasin = $capaciteMagasinRepository;
        $this->repo_categorie = $categorieRepository;

        $this->table_famille = Famille::class;
        $this->table_produit = Produit::class;
        $this->table_achat = Achat::class;
        $this->table_reception = Reception::class;
        $this->table_uval = Uval::class;
        $this->table_margeprix = Margeprix::class;
        $this->table_stock = Stock::class;
        $this->table_sortiestock = SortieStock::class;
        $this->table_magasin = Magasin::class;
        $this->table_capacite_magasin = CapaciteMagasin::class;
        $this->table_categorie = Categorie::class;

        $this->db = $managerRegistry->getManager();
    }

    public function insert_to_db($entity)
    {

        $this->db->persist($entity);
        $this->db->flush();
    }

    public function delete_data($entity)
    {

        $this->db->remove($entity);
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

    public function new_sortie($data)
    {
        // Récupérer le produit
        $produit = $this->repo_produit->find($data['produit']);

        // Déterminer le début et la fin de la journée
        $todayStart = (new \DateTime())->setTime(0, 0, 0);
        $todayEnd   = (new \DateTime())->setTime(23, 59, 59);

        // Vérifier si une sortie existe déjà pour ce produit aujourd'hui
        $sortieExistante = $this->repo_sortiestock->createQueryBuilder('s')
            ->where('s.produit = :produit')
            ->andWhere('s.dateSortie BETWEEN :start AND :end')
            ->setParameter('produit', $produit)
            ->setParameter('start', $todayStart)
            ->setParameter('end', $todayEnd)
            ->getQuery()
            ->getOneOrNullResult();

        if ($sortieExistante) {
            // Si une sortie existe déjà aujourd'hui, on incrémente les valeurs
            $sortieExistante->setQteSortie($sortieExistante->getQteSortie() + $data['quantite']);
            $sortieExistante->setValeur($sortieExistante->getValeur() + $data['prixTotal']);

            $this->insert_to_db($sortieExistante);
        } else {
            // Sinon, créer une nouvelle sortie
            $sortie = new $this->table_sortiestock;
            $sortie->setProduit($produit);
            $sortie->setQteSortie($data['quantite']);
            $sortie->setValeur($data['prixTotal']);
            $sortie->setDateSortie(new \DateTime());
            $sortie->setProfitUnitaire(0);
            $sortie->setProfitTotal(0);

            $this->insert_to_db($sortie);
        }
    }


    //on cree la methode qui permettra d'enregistrer les receptions du post dans la bd

    public function new_reception($data)
    {
        // 1. Récupération des entités nécessaires
        $achat = $this->repo_achat->find($data['achat']);
        if (!$achat) {
            throw new \Exception("Achat introuvable pour l'ID " . $data['achat']);
        }

        $produit = $this->repo_produit->find($achat->getProduit()->getId());
        if (!$produit) {
            throw new \Exception("Produit introuvable pour l'ID " . $achat->getProduit()->getId());
        }

        $magasin = $this->repo_magasin->find($data['magasin']);
        if (!$magasin) {
            throw new \Exception("Magasin introuvable pour l'ID " . $data['magasin']);
        }

        // 2. Calculer le début et la fin de la journée pour comparer les dates
        $today = new \DateTime();
        $todayStart = (clone $today)->setTime(0, 0, 0);
        $todayEnd   = (clone $today)->setTime(23, 59, 59);

        // 3. Vérifier si une réception existe déjà pour ce même achat, magasin et date du jour
        $receptionExistante = $this->repo_reception->createQueryBuilder('r')
            ->where('r.commande = :commande')
            ->andWhere('r.magasin = :magasin')
            ->andWhere('r.date_reception BETWEEN :start AND :end')
            ->setParameter('commande', $achat)
            ->setParameter('magasin', $magasin)
            ->setParameter('start', $todayStart)
            ->setParameter('end', $todayEnd)
            ->getQuery()
            ->getOneOrNullResult();

        if ($receptionExistante) {
            // 4a. Mise à jour de la réception existante
            $receptionExistante->setQteRec($receptionExistante->getQteRec() + $data['quantite']);
            $receptionExistante->setPrixTotal($receptionExistante->getPrixTotal() + $data['prixTotal']);
            $receptionExistante->setQteUnitVal($data['qteUnitVal']);
            $receptionExistante->setQteTotVal($data['qteTotVal']);
            $receptionExistante->setPrixTotVal($data['prixTotVal']);

            $this->insert_to_db($receptionExistante);
        } else {
            // 4b. Création d'une nouvelle réception
            $reception = new $this->table_reception;
            $reception->setCommande($achat);
            $reception->setQteRec($data['quantite']);
            $reception->setPrixTotal($data['prixTotal']);
            $reception->setMagasin($magasin);
            $reception->setDateReception(new \DateTime());
            $reception->setQteUnitVal($data['qteUnitVal']);
            $reception->setQteTotVal($data['qteTotVal']);
            $reception->setPrixTotVal($data['prixTotVal']);

            $this->insert_to_db($reception);
        }

        // 5. Mise à jour de la capacité du magasin
        $cap_tot = $magasin->getCapacite();
        $cap_act = $cap_tot - floatval($data['quantite']);
        $cap_mag = new $this->table_capacite_magasin;
        $cap_mag->setMagasin($magasin);
        $cap_mag->setCapaciteActuel($cap_act);

        $this->insert_to_db($cap_mag);
    }



    //on cree la methode qui permettra d'enregistrer les achats du post dans la bd

    function new_achat($data)
    {
        $produit = $this->repo_produit->find($data['produit']);
        $dateAchat = new \DateTime(); // Date actuelle
        $dateAchatDebut = (clone $dateAchat)->setTime(0, 0, 0);
        $dateAchatFin = (clone $dateAchat)->setTime(23, 59, 59);

        // Vérifier si un achat existe déjà pour ce produit et cette date
        $achatExistant = $this->repo_achat->createQueryBuilder('a')
            ->where('a.produit = :produit')
            ->andWhere('a.dateachat BETWEEN :dateDebut AND :dateFin')
            ->setParameter('produit', $produit)
            ->setParameter('dateDebut', $dateAchatDebut)
            ->setParameter('dateFin', $dateAchatFin)
            ->getQuery()
            ->getOneOrNullResult();

        if ($achatExistant) {
            // Mettre à jour l'achat existant
            $achatExistant->setQte($achatExistant->getQte() + $data['quantite']);
            $achatExistant->setPrixATotal($achatExistant->getPrixTotal() + $data['prixTotal']);
            $achatExistant->setDateachat($dateAchat);
            $this->insert_to_db($achatExistant);
        } else {
            // Créer un nouvel achat
            $achat = new $this->table_achat;
            $achat->setProduit($produit);
            $achat->setQte($data['quantite']);
            $achat->setPrixATotal($data['prixTotal']);
            $achat->setDateachat($dateAchat);
            $this->insert_to_db($achat);
        }
    }

    //on cree la methode qui permettra d'enregistrer les produits du post dans la bd
    function new_produit($data)
    {
        $this->multiple_row($data);

        $categorie = $this->repo_categorie->find($data['id_categorie']);
        $unite_achat = $this->repo_uval->find($data['uniteAchat']);
        $unite_vente = $this->repo_uval->find($data['uniteVente']);

        $produit = new $this->table_produit;
        $produit->setCategorie($categorie);
        $produit->setNom(ucfirst($data['produit']));
        $produit->setStatut('actif');
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

    //on cree la methode qui permettra d'enregistrer les categories de produits du post dans la bd
    function new_categorie($data)
    {
        $this->multiple_row($data);

        $categorie = new $this->table_categorie;
        // $famille->setUser($user);

        $categorie->setNomCat(ucfirst($data['categorie']));
        // récupérer l'objet Famille via le repository
        $famille = $this->repo_famille->find($data['famille_id']);
        if (!$famille) {
            throw new \Exception("Famille introuvable pour l'ID " . $data['famille_id']);
        }
        $categorie->setFamille($famille);
        $this->insert_to_db($categorie);
    }

    function new_uval($data)
    {
        $unite = new $this->table_uval;
        $unite->setNomuval($data['emballage']);
        $this->insert_to_db($unite);
    }

    function new_magasin($data)
    {
        $magasin = new $this->table_magasin;
        $magasin->setNom($data['magasin']);
        $magasin->setCapacite($data['capacite']);

        $this->insert_to_db($magasin);
    }
}
