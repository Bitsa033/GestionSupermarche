<?php

namespace App\Controller;

use App\Entity\Famille;
use App\Entity\Produit;
use App\Entity\Uval;
use App\Service\Service;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
    * @Route("/api/", name="api", methods={"GET"})
    */
class UtilsServiceController extends AbstractController
{
    /**
     * Famille .....................................................
     */

    /**
    * @Route("famille/findall", name="famillefindall", methods={"GET"})
    */
    public function famillefindall(Service $service): Response
    {
        $data=[];
        $f=$service->repo_famille->findAll();
        foreach ($f as $value) {
            $data[]=[
                'id'=>$value->getId(),
                'nom'=>$value->getNom()
            ]; 
        }
        return $this->json($data);
    }

    /**
     * @param int $id
    * @Route("famille/find/{id}", name="famillefind", methods={"GET"})
    */
    public function famillefind(Service $service, $id): Response
    {
        $f=$service->repo_famille->find($id);
        $data=[
            'id'=>$f->getId(),
            'nom'=>$f->getNom()
        ];
        return $this->json($data);
    }

    /**
    * @Route("famille/store", name="famillestore", methods={"POST"})
    */
    public function famillestore(Service $service, Request $request): Response
    {
        $nom=$request->request->get('nom');
        
        $f=new Famille();
        $f->setNom($nom);
        $service->insert_to_db($f);

        $data=[
            'id'=>$f->getId(),
            'nom'=>$f->getNom()
        ];
        
        return $this->json([
            'statut'=>'success',
            'message'=>'donnée enregistrée avec succès',
            'data'=>$data
        ]);
        
    }

    /**
    * @Route("famille/update/{id}", name="familleupdate", methods={"PUT"})
    */
    public function familleupdate(Service $service,$id, Request $request): Response
    {
        $nom=$request->query->get('nom');
        
        $f=$service->repo_famille->find($id);
        $f->setNom($nom);
        $service->db->flush($f);

        $data=[
            'id'=>$f->getId(),
            'nom'=>$f->getNom()
        ];
        
        return $this->json([
            'statut'=>'success',
            'message'=>'donnée mise à jour avec succès',
            'data'=>$data
        ]);
    }

    /**
    * @Route("famille/delete/{id}", name="familledelete", methods={"DELETE"})
    */
    public function familledelete(Service $service,$id): Response
    {
        $f=$service->repo_famille->find($id);
        $service->delete_data($f);

        $data=[
            'id'=>$f->getId(),
            'nom'=>$f->getNom()
        ];

        return $this->json([
            'statut'=>'succès',
            'message'=>"Donnée supprimée avec succès",
            'data'=>$data
        ]);
    }

    /**
     * Emballage .....................................................
     */


    /**
    * @Route("emballage/findall", name="emballagefindall", methods={"GET"})
    */
    public function emballagefindall(Service $service): Response
    {
        $data=[];
        $f=$service->repo_uval->findAll();
        foreach ($f as $value) {
            $data[]=[
                'id'=>$value->getId(),
                'nom'=>$value->getNomuval()
            ]; 
        }
        return $this->json($data);
    }

    /**
     * @param int $id
    * @Route("emballage/find/{id}", name="emballagefind", methods={"GET"})
    */
    public function emballagefind(Service $service, $id): Response
    {
        $f=$service->repo_uval->find($id);

        $data[]=[
            'id'=>$f->getId(),
            'nom'=>$f->getNomuval()
        ]; 
        
        return $this->json($data);
    }

    /**
    * @Route("emballage/store", name="emballagestore", methods={"POST"})
    */
    public function emballagestore(Service $service,Request $request): Response
    {
        $nom=$request->get('nom');
        $e=new Uval();
        $e->setNomuval($nom);
        $service->insert_to_db($e);

        $data=[
            'id'=>$e->getId(),
            'nom'=>$e->getNomuval()
        ];

        return $this->json([
            'statut'=>'succès',
            'message'=>"Donnée supprimée avec succès",
            'data'=>$data
        ]);
    }

    /**
    * @Route("emballage/update/{id}", name="emballageupdate", methods={"PUT"})
    */
    public function emballageupdate(Service $service,$id, Request $request): Response
    {
        $nom=$request->get('nom');
        $e=$service->repo_uval->find($id);
        $e->setNomuval($nom);
        $service->db->flush($e);

        $data=[
            'id'=>$e->getId(),
            'nom'=>$e->getNomuval()
        ];

        return $this->json([
            'statut'=>'succès',
            'message'=>"Donnée mise à jour avec succès",
            'data'=>$data
        ]);
    }

    /**
    * @Route("emballage/delete/{id}", name="emballagedelete", methods={"DELETE"})
    */
    public function emballagedelete(Service $service,$id): Response
    {
        $e=$service->repo_uval->find($id);
        $service->delete_data($e);

        $data=[
            'id'=>$e->getId(),
            'nom'=>$e->getNomuval()
        ];

        return $this->json([
            'statut'=>'succès',
            'message'=>"Donnée supprimée avec succès",
            'data'=>$data
        ]);
    }

    /**
     * Produit .....................................................
     */


    /**
    * @Route("produit/findall", name="produitfindall", methods={"GET"})
    */
    public function produitfindall(Service $service): Response
    {
        $data=[];
        $f=$service->repo_produit->findAll();
        foreach ($f as $value) {
            $data[]=[
                'id'=>$value->getId(),
                'nom'=>$value->getNom(),
                'prix_achat'=>$value->getPrixAchat(),
                'prix_vente'=>$value->getPrixVente(),
                'emballage_achat'=>$value->getUniteAchat()->getNomuval(),
                'emballage_vente'=>$value->getUniteVente()->getNomuval()
            ]; 
        }
        return $this->json($data);
    }

    /**
     * @param int $id
    * @Route("produit/find/{id}", name="produitfind", methods={"GET"})
    */
    public function produitfind(Service $service, $id): Response
    {
        $f=$service->repo_produit->find($id);

        $data[]=[
            'id'=>$f->getId(),
            'nom'=>$f->getNom(),
            'famille'=>$f->getFamille()->getNom(),
            'prix_achat'=>$f->getPrixAchat(),
            'prix_vente'=>$f->getPrixVente(),
            'emballage_achat'=>$f->getUniteAchat()->getNomuval(),
            'emballage_vente'=>$f->getUniteVente()->getNomuval()
        ]; 
        
        return $this->json($data);
    }

    /**
    * @Route("produit/store", name="produittore", methods={"POST"})
    */
    public function produittore(Service $service,Request $request): Response
    {
        $nom=$request->get('nom');
        $code=$request->get('code');
        $unite_achat=$request->get('unite_achat');
        $unite_vente=$request->get('unite_vente');
        $prix_achat=$request->get('prix_achat');
        $prix_vente=$request->get('prix_vente');

        $data2=[
            'produit'=>$nom,
            'code'=>$code,
            'prix_achat'=>$prix_achat,
            'prix_vente'=>$prix_vente,
            'unite_achat'=>$unite_achat,
            'unite_vente'=>$unite_vente,
            
        ];
        $em=new Uval();
        $ua=$em->setNomuval($unite_achat);
        $uv=$em->setNomuval($unite_vente);

        $e=new Produit();
        $e->setNom($nom);
        $e->setCode($code);
        $e->setStatut('Actif');
        $e->setPrixAchat($prix_achat);
        $e->setPrixVente($prix_vente);
        $e->setUniteAchat($ua);
        $e->setUniteVente($uv);
        $service->insert_to_db($e);

        $data=[
            'id'=>$e->getId(),
            'nom'=>$e->getNom(),
            'statut'=>$e->getStatut(),
            'code'=>$e->getCode(),
            'prix_achat'=>$e->getPrixAchat(),
            'prix_vente'=>$e->getPrixVente(),
            'emballage_achat'=>$e->getUniteAchat(),
            'emballage_vente'=>$e->getUniteVente()
            
        ];


        return $this->json([
            'statut'=>'succès',
            'message'=>"Donnée enregistrée avec succès",
            'data'=>$data
        ]);
    }

    /**
    * @Route("produit/update/{id}", name="produitupdate", methods={"PUT"})
    */
    public function produitupdate(Service $service,$id, Request $request): Response
    {
        $nom=$request->get('nom');
        $famiile_nom=$request->get('famille');
        $prix_achat=$request->get('prix_achat');
        $prix_vente=$request->get('prix_vente');
        $e=$service->repo_produit->find($id);
        if (!empty($nom)) {
            $e->setNom($nom);
        }
        elseif (!empty($famiile_nom)) {
            $famiile=$e->getFamille()->setNom($famiile_nom);
            $e->setFamille($famiile);
        }
        elseif (!empty($prix_achat)) {
            $e->setPrixAchat($prix_achat);
        }
        elseif (!empty($prix_vente)) {
            $e->setPrixVente($prix_vente);
        }
        $service->insert_to_db($e);

        $data=[
            'id'=>$e->getId(),
            'nom'=>$e->getNom(),
            'famille'=>$e->getFamille()->getNom(),
            'prix_achat'=>$e->getPrixAchat(),
            'prix_vente'=>$e->getPrixVente(),
            'emballage_achat'=>$e->getUniteAchat()->getNomuval(),
            'emballage_vente'=>$e->getUniteVente()->getNomuval()
            
        ];

        return $this->json([
            'statut'=>'succès',
            'message'=>"Donnée mise à jour avec succès",
            'data'=>$data
        ]);
    }

    /**
    * @Route("produit/delete/{id}", name="produitdelete", methods={"DELETE"})
    */
    public function produitdelete(Service $service,$id): Response
    {
        $a=$service->repo_achat->findOneBy(['produit'=>$id]);
        
        $e=$service->repo_produit->find($id);
        
        $data=[
            'id_achat'=>$a->getId(),
            'id_produit'=>$e->getId(),
            'nom_produit'=>$e->getNom()
        ];
        
        if ($data['id_achat'] !=null) {
            return $this->json([
                'statut'=>'error',
                'message'=>"Cette donnée ne peut etre supprimée car elle est utilisée par un autre proccessus ",
                'data'=>$data
            ]);
        }
        
        $service->delete_data($e);

        return $this->json([
            'statut'=>'succès',
            'message'=>"Donnée supprimée avec succès",
            'data'=>$data
        ]);
    }
}

