<?php

namespace App\Controller;

use App\Entity\Famille;
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
     * Emballages .....................................................
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
}

