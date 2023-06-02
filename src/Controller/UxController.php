<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UxController extends AbstractController
{
    /**
     * @Route("/ux", name="app_ux")
     */
    public function index(): Response
    {
        return $this->render('ux/index.html.twig', [
            'controller_name' => 'UxController',
        ]);
    }
}
