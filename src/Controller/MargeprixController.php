<?php

namespace App\Controller;

use App\Entity\Margeprix;
use App\Form\MargeprixType;
use App\Repository\MargeprixRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("margeprix_")
 */
class MargeprixController extends AbstractController
{
    /**
     * @Route("index", name="margeprix_index", methods={"GET"})
     */
    public function index(MargeprixRepository $margeprixRepository): Response
    {
        return $this->render('margeprix/index.html.twig', [
            'margeprixes' => $margeprixRepository->findAll(),
        ]);
    }

    /**
     * @Route("new", name="margeprix_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $margeprix = new Margeprix();
        $form = $this->createForm(MargeprixType::class, $margeprix);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($margeprix);
            $entityManager->flush();

            return $this->redirectToRoute('margeprix_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('margeprix/new.html.twig', [
            'margeprix' => $margeprix,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("{id}", name="margeprix_show", methods={"GET"})
     */
    public function show(Margeprix $margeprix): Response
    {
        return $this->render('margeprix/show.html.twig', [
            'margeprix' => $margeprix,
        ]);
    }

    /**
     * @Route("{id}_edit", name="margeprix_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Margeprix $margeprix, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MargeprixType::class, $margeprix);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('margeprix_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('margeprix/edit.html.twig', [
            'margeprix' => $margeprix,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("{id}", name="margeprix_delete", methods={"POST"})
     */
    public function delete(Request $request, Margeprix $margeprix, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$margeprix->getId(), $request->request->get('_token'))) {
            $entityManager->remove($margeprix);
            $entityManager->flush();
        }

        return $this->redirectToRoute('margeprix_index', [], Response::HTTP_SEE_OTHER);
    }
}
