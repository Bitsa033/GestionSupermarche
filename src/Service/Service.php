<?php

namespace App\Service;

use App\Entity\Achat;
use App\Entity\Famille;
use App\Entity\Margeprix;
use App\Entity\Produit;
use App\Repository\UvalRepository;
use App\Repository\AchatRepository;
use App\Entity\Stock;
use App\Entity\Uval;
use App\Repository\FamilleRepository;
use App\Repository\MargeprixRepository;
use App\Repository\ProduitRepository;
use App\Repository\StockRepository;
use Doctrine\Persistence\ManagerRegistry;

class Service{
    
    public $table_user;
    public $table_famille;
    public $table_produit;
    public $table_achat;
    public $table_uval;
    public $table_margeprix;
    public $table_stock;
    public $table_profit;

    public $repo_user;
    public $repo_famille;
    public $repo_produit;
    public $repo_achat;
    public $repo_uval;
    public $repo_margeprix;
    public $repo_stockt;
    public $repo_profit;

    public $db;

    function __construct(
                        FamilleRepository $familleRepository,
                        ProduitRepository $produitRepository,
                        AchatRepository $achatRepository,
                        UvalRepository $uvalRepository,
                        MargeprixRepository $margeprixRepository,
                        StockRepository $stockRepository,
                        ManagerRegistry $managerRegistry
    )
    {
        $this->repo_famille=$familleRepository;
        $this->repo_produit=$produitRepository;
        $this->repo_achat=$achatRepository;
        $this->repo_uval=$uvalRepository;
        $this->repo_margeprix=$margeprixRepository;
        $this->repo_stockt=$stockRepository;

        $this->table_famille=Famille::class;
        $this->table_produit= Produit::class;
        $this->table_achat=Achat::class;
        $this->table_uval= Uval::class;
        $this->table_margeprix= Margeprix::class;
        $this->table_stock= Stock::class;

        $this->db=$managerRegistry->getManager();

        //on cherche l'utilisateur connecté
        // $user= $this->getUser();
        // if (!$user) {
        //   return $this->redirectToRoute('app_login');
        // }

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

    //on cree la methode qui permettra d'enregistrer les achats du post dans la bd
         
    function new_achat($data,$idProduit,$idUval)
    {
        $this->multiple_row($data);

        $produit=$this->repo_produit->find($idProduit);
        $uniteAchat=$this->repo_uval->find($idUval);
    
        $achat = new $this->table_achat;
        $achat->setProduit($produit);
        $achat->setUniteAchat($uniteAchat);
        $achat->setQteAchat($data['quantiteAchat']);
        $achat->setPrixAchatUnitaire($data['prixAchatUnitaire']);
        $achat->setPrixAchatTotal($data['prixAchatTotal']);
        $achat->setDateachat(new \DateTime());
        // $stock->setRef(strtoupper($data['ref']));
        $this->insert_to_db($achat);
    }

    //on cree la methode qui permettra d'enregistrer les produits du post dans la bd
    function new_produit($data)
    {
        $this->multiple_row($data);
        
        $famille=$this->repo_famille->find($data['id_famille']);
        $produit = new $this->table_produit;
        $produit->setFamille($famille);
        $produit->setNom(ucfirst($data['produit']));
        $produit->setAlerte($data['alerte']);
        $produit->setCode(strtoupper($data['code']));
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

    // public function new_niveau($data, User $user)
    // {
    //     $this->multiple_row($data);
        
    //     $classe= new $this->table_niveau;
    //     $classe->setUser($user);
    //     $classe->setNom(ucfirst($data['nom']));
    //     $classe->setCreatedAt(new \datetime);
    //     $this->db->persist($classe);
    //     $this->db->flush();
    // }

    // public function new_semestre($data, User $user)
    // {
    //     $this->multiple_row($data);
        
    //     $semestre= new $this->table_semestre;
    //     $semestre->setUser($user);
    //     $semestre->setNom(ucfirst($data['nom']));
    //     $semestre->setCreatedAt(new \datetime);
    //     $this->db->persist($semestre);
    //     $this->db->flush();
    // }

    // public function new_matiere($data, User $user)
    // {
    //     $matiere = new $this->table_matiere;
    //     $matiere->setUser($user);
    //     $matiere->setNom(ucfirst($data['nom']));
    //     $matiere->setCreatedAt(new \datetime);

    //     $ue=new $this->table_ue;
    //     $ue->setFiliere($this->repo_filiere->find($data['filiere']));
    //     $ue->setNiveau($this->repo_niveau->find($data['niveau']));
    //     $ue->setSemestre($this->repo_semestre->find($data['semestre']));
    //     $ue->setUser($user);
    //     $ue->setMatiere($matiere);
    //     $ue->setNote($data['note']);
    //     $ue->setCredit($data['note']/20);
    //     $ue->setCode($data['code']);
    //     $ue->setCreatedAt(new \DateTime);
    //     $this->db->persist($ue);
    //     $this->db->flush();
    // }

    // public function affecter_matiere($data)
    // {
    //     $object = new $this->table_ue;
    //     $object->setUser($data['user']);
    //     $object->setMatiere($this->repo_matiere->find($data['matiere']));
    //     $object->setFiliere($this->repo_filiere->find($data['filiere']));
    //     $object->setNiveau($this->repo_niveau->find($data['niveau']));
    //     $object->setSemestre($this->repo_semestre->find($data['semestre']));
    //     $object->setCredit(4);
    //     $object->setCreatedAt(new \datetime);
    //     $this->db->persist($object);
    //     $this->db->flush();
    // }

    // public function new_etudiant($data)
    // {

    //     //on enregistre
    //     $etudiant = $this->table_etudiant;
    //     $etudiant->setUser($data['user']);
    //     $etudiant->setNom(ucfirst($data['nom']));
    //     $etudiant->setPrenom(ucfirst($data['prenom']));
    //     $etudiant->setSexe(ucfirst($data['sexe']));
    //     $etudiant->setCreatedAt(new \datetime);
    //     //on inscrit
    //     $inscription=new $this->table_inscription;
    //     $inscription->setEtudiant($etudiant);
    //     $inscription->setFiliere($this->repo_filiere->find($data['filiere']));
    //     $inscription->setNiveau($this->repo_niveau->find($data['niveau']));
    //     $inscription->setCreatedAt(new \datetime);
    //     //on retourne les resultats
    //     $this->db->persist($inscription);
    //     $this->db->flush();

    // }

    // public function affecter_etudiant($data)
    // {
        
    //     $object = new $this->table_inscription;
    //     $object->setUser($this->repo_user->find($data['user']));
    //     $object->setEtudiant($this->repo_etudiant->find($data['etudiant']));
    //     $object->setNiveau($this->repo_niveau->find($data['niveau']));
    //     $object->setFiliere($this->repo_filiere->find($data['filiere']));
    //     $object->setCreatedAt(new \datetime);
    //     $this->db->persist($object);
    //     $this->db->flush();
    // }

    // function new_note($data)
    // {
    //     $object = new $this->table_note;
    //     $object->setUser($this->repo_user->find($data['user']));
    //     $object->setInscription($this->repo_inscription->find($data['inscription']));
    //     $object->setUe($this->repo_ue->find($data['cours']));
    //     $object->setSemestre($this->repo_semestre->find($data['semestre']));
    //     $object->setMoyenne($data['note']);
    //     $object->setCreatedAt(new \datetime);
    //     $this->db->persist($object);
    //     $this->db->flush();
    // }
    
}