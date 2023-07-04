<?php

namespace App\Controller;

use App\Entity\Famille;
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
    * @Route("findemballages", name="findemballages", methods={"GET"})
    */
    public function findemballages(Service $service): Response
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
    * @Route("findemballage/{id}", name="findemballage", methods={"GET"})
    */
    public function findemballage(Service $service, $id): Response
    {
        $f=$service->repo_uval->find($id);
        foreach ($f as $value) {
            $data[]=[
                'id'=>$value->getId(),
                'nom'=>$value->getNom()
            ]; 
        }
        return $this->json($data);
    }

    /**
    * @Route("storeemballage", name="storeemballage", methods={"POST"})
    */
    public function storeemballage(): Response
    {
        return $this->json("Store family of product");
    }

    /**
    * @Route("updateemballage", name="updateemballage", methods={"PUT"})
    */
    public function updateemballage(): Response
    {
        return $this->json("Update family of product");
    }

    /**
    * @Route("deleteemballage", name="deleteemballage", methods={"DELETE"})
    */
    public function deleteemballage(): Response
    {
        return $this->json("Delete emballage of product");
    }
}

