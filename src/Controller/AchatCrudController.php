<?php

namespace App\Controller;

use App\Service\Service;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AchatCrudController extends AbstractController
{
    /**
     * @Route("/achat_crud", name="app_achat_crud")
     */
    public function index(Service $service)
    {
            $rep= $service->repo_produit->prod_qty_price(1);
            
        // try {
        // } catch (\Throwable $th) {
        //     die('Erreur, base de données introuvable, si vous utilisez un logiciel de base de donées,
        //     veuillez l\'activé !');
        // }
        $var= array(
            "Akono"=>"16 ans",
            "Marc"=>"10 ans",
            "Lucien"=>"29 ans"
        );
        foreach ($var as $key => $value) {
            
            $vari= array(
                "Akono"=>1,
                "Marc"=>"10 ans",
                "Lucien"=>"29 ans"
            );
            
        }
        //var_dump("i");
        return new JsonResponse($rep);
        //create a new Response object

      //set the return value
      //$response->setContent('Hello World!');

      //make sure we send a 200 OK status
      //$response->setStatusCode(Response::HTTP_OK);
      
      // set the response content type to plain text
      //$response->headers->set('Content-Type', 'text/plain');
      
      // send the response with appropriate headers
      //$response->send();

      //return $response;
    }
}
