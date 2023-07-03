<?php

namespace App\Controller;

use App\Service\Service;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    * @Route("findallfamille", name="findallfamille", methods={"GET"})
    */
    public function findallfamille(Service $service): Response
    {
        $e=$service->repo_famille->familles();
        return $this->json($e);
    }

    /**
    * @Route("findfamille_{id}", name="findfamille", methods={"GET"})
    */
    public function findfamille(Service $service, $id): Response
    {
        $f=$service->repo_famille->find($id);
        return $this->json($f);
    }

    /**
    * @Route("storefamille", name="storefamille", methods={"POST"})
    */
    public function storefamille(): Response
    {
        return $this->json("Store family of product");
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
    * @Route("findallemballage", name="findallemballage", methods={"GET"})
    */
    public function findallemballage(Service $service): Response
    {
        $e=$service->repo_produit->products();
        return $this->json($e);
    }

    /**
    * @Route("findemballage", name="findemballage", methods={"GET"})
    */
    public function findemballage(): Response
    {
        return $this->json("Display one family for product");
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

