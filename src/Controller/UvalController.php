<?php

namespace App\Controller;

use App\Entity\Uval;
use App\Form\UvalType;
use App\Service\Service;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("uval_")
 */
class UvalController extends AbstractController
{
    /**
     * @Route("uval_index", name="uval_index", methods={"GET"})
     */
    public function index(Service $service): Response
    {
        return $this->render('uval/index.html.twig', [
            'uvals' => $service->repo_uval->findAll(),
        ]);
    }

    /**
     * @Route("new", name="uval_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $uval = new Uval();
        $form = $this->createForm(UvalType::class, $uval);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($uval);
            $entityManager->flush();

            return $this->redirectToRoute('uval_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('uval/new.html.twig', [
            'uval' => $uval,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("{id}", name="uval_show", methods={"GET"})
     */
    public function show(Uval $uval): Response
    {
        return $this->render('uval/show.html.twig', [
            'uval' => $uval,
        ]);
    }

    /**
     * @Route("{id}_edit", name="uval_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Uval $uval, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UvalType::class, $uval);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('uval_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('uval/edit.html.twig', [
            'uval' => $uval,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="uval_delete", methods={"POST"})
     */
    public function delete(Request $request, Uval $uval, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$uval->getId(), $request->request->get('_token'))) {
            $entityManager->remove($uval);
            $entityManager->flush();
        }

        return $this->redirectToRoute('uval_index', [], Response::HTTP_SEE_OTHER);
    }
}
