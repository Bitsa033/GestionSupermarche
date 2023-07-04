<?php

namespace App\Controller;

use App\Entity\Famille;
use App\Service\Service;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
* @Route("api_", name="api_")
*/
class UtilsServiceController extends AbstractController
{
    /**
     * Familles .....................................................
     */


    /**
    * @Route("findfamilles", name="findfamilles", methods={"GET"})
    */
    public function findfamilles(Service $service): Response
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
    * @Route("findfamille/{id}", name="findfamille", methods={"GET"})
    */
    public function findfamille(Service $service, $id): Response
    {
        $f=$service->repo_famille->find($id);
        $data=[
            'id'=>$f->getId(),
            'nom'=>$f->getNom()
        ];
        return $this->json($data);
    }

    /**
    * @Route("storefamille", name="storefamille", methods={"POST"})
    */
    public function storefamille(Service $service, Request $request): Response
    {
        $nom=$request->request->get('nom');
        if (!empty($nom)) {
            $f=new Famille();
            $f->setNom($nom);
            $service->db->persist($f);

            $data=[
                'id'=>$f->getId(),
                'nom'=>$f->getNom()
            ];
            
            return $this->json([
                'statut'=>'success',
                'message'=>'request was done successfuly',
                'data'=>$data
            ]);
        } else {
            return $this->json([
                'statut'=>'error',
                'message'=>'name is required'
            ]);
        }
        
    }

    /**
    * @Route("updatefamille", name="updatefamille", methods={"PUT"})
    */
    public function updatefamille(): Response
    {
        return $this->json("Update family of product");
    }

    /**
    * @Route("deletefamille", name="deletefamile", methods={"DELETE"})
    */
    public function deletefamille(): Response
    {
        return $this->json("Delete family of product");
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

