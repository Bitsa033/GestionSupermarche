<?php

namespace App\Controller;

use App\Entity\CapaciteMagasin;
use App\Form\CapaciteMagasinType;
use App\Repository\CapaciteMagasinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CapaciteMagasinController extends AbstractController
{
    /**
     * @Route("capacite_magasin_index", name="capacite_magasin_index", methods={"GET"})
     */
    public function index(CapaciteMagasinRepository $capaciteMagasinRepository): Response
    {
        return $this->render('capacite_magasin/index.html.twig', [
            'capacite_magasins' => $capaciteMagasinRepository->findAll(),
        ]);
    }

    /**
     * @Route("capacite_magasin_new", name="capacite_magasin_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $capaciteMagasin = new CapaciteMagasin();
        $form = $this->createForm(CapaciteMagasinType::class, $capaciteMagasin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $capaciteMagasin->setNbActuel(0);
            $entityManager->persist($capaciteMagasin);
            $entityManager->flush();

            return $this->redirectToRoute('capacite_magasin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('capacite_magasin/new.html.twig', [
            'capacite_magasin' => $capaciteMagasin,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("capacite_magasin_show_{id}", name="capacite_magasin_show", methods={"GET"})
     */
    public function show(CapaciteMagasin $capaciteMagasin): Response
    {
        return $this->render('capacite_magasin/show.html.twig', [
            'capacite_magasin' => $capaciteMagasin,
        ]);
    }

    /**
     * @Route("capacite_magasin_edit_{id}", name="capacite_magasin_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CapaciteMagasin $capaciteMagasin, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CapaciteMagasinType::class, $capaciteMagasin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('capacite_magasin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('capacite_magasin/edit.html.twig', [
            'capacite_magasin' => $capaciteMagasin,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("capacite_magasin_delete_{id}", name="capacite_magasin_delete", methods={"POST"})
     */
    public function delete(Request $request, CapaciteMagasin $capaciteMagasin, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$capaciteMagasin->getId(), $request->request->get('_token'))) {
            $entityManager->remove($capaciteMagasin);
            $entityManager->flush();
        }

        return $this->redirectToRoute('capacite_magasin_index', [], Response::HTTP_SEE_OTHER);
    }
}
