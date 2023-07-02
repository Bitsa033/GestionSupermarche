<?php

namespace App\Controller;

use App\Service\Service;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api_", name="app_api_")
 */
class ApiController extends AbstractController
{
    /**
     * @Route("produits", name="produits", methods={"GET"})
     */
    public function produits(Service $service):Response
    {
        try {
            $produits=$service->repo_produit->products();
        } catch (\Throwable $th) {
            die($th->getMessage());
        }
        return new JsonResponse($produits);
    }

    /**
     * @Route("achats", name="achats", methods={"GET"})
     */
    public function achats(Service $service):Response
    {
        try {
            $produits=$service->repo_produit->achats();
        } catch (\Throwable $th) {
            die($th->getMessage());
        }
        return new JsonResponse($produits);
    }

    /**
     * @Route("receptions", name="receptions", methods={"GET"})
     */
    public function receptions(Service $service):Response
    {
        try {
            $produits=$service->repo_produit->stocks();
        } catch (\Throwable $th) {
            die($th->getMessage());
        }
        return new JsonResponse($produits);
    }

    /**
     * @Route("stocks", name="stocks", methods={"GET"})
     */
    public function stocks(Service $service):Response
    {
        try {
            $produits=$service->repo_produit->stocks();
        } catch (\Throwable $th) {
            die($th->getMessage());
        }
        return new JsonResponse($produits);
    }
}
