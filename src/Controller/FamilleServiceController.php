<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
* @Route("familleservice_", name="familleservice_")
*/
class FamilleServiceController extends AbstractController
{
    /**
    * @Route("findall", name="findall", methods={"GET"})
    */
    public function findall(): Response
    {
        return $this->json("Display all families for product");
    }

    /**
    * @Route("find", name="find", methods={"GET"})
    */
    public function find(): Response
    {
        return $this->json("Display one family for product");
    }

    /**
    * @Route("store", name="store", methods={"POST"})
    */
    public function store(): Response
    {
        return $this->json("Store family of product");
    }

    /**
    * @Route("update", name="update", methods={"PUT"})
    */
    public function update(): Response
    {
        return $this->json("Update family of product");
    }

    /**
    * @Route("delete", name="delete", methods={"DELETE"})
    */
    public function delete(): Response
    {
        return $this->json("Delete family of product");
    }
}
