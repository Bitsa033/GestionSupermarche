<?php

namespace App\Controller;

use App\Entity\Famille;
use App\Form\FamilleType;
use App\Repository\FamilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("famille_")
 */
class FamilleController extends AbstractController
{
    /**
     * @Route("nb", name="famille_nb")
     */
    public function nb(SessionInterface $session, Request $request)
    {
        if (!empty($request->request->get('nb_row'))) {
            $nb_of_row = $request->request->get('nb_row');
            $get_nb_row = $session->get('nb_row', []);
            if (!empty($get_nb_row)) {
                $session->set('nb_row', $nb_of_row);
            }
            $session->set('nb_row', $nb_of_row);
            //dd($session);
        }
        return $this->redirectToRoute('famille_index');
    }

    /**
     * Insertion et affichage des filieres
     * @Route("index", name="famille_index")
     */
    public function famille(SessionInterface $session, FamilleRepository $familleRepository, Request $request, ManagerRegistry $end)
    {
        //on cherche l'utilisateur connecté
        $user = $this->getUser();
        
        // if (!$user) {
        //     return $this->redirectToRoute('app_login');
        // }
        //on recupere la valeur du nb_row stocker dans la session
        if (!empty($session->get('nb_row', []))) {
            $sessionLigne = $session->get('nb_row', []);
        }
        else{
            $sessionLigne = 1;
        }
        $sessionNb = $sessionLigne;
        //on cree un tableau qui permettra de generer plusieurs champs dans le post
        //en fonction de la valeur de nb_row
        $nb_row = array(1);
        //pour chaque valeur du compteur i, on ajoutera un champs de plus en consirerant que 
        //nb_row par defaut=1
        if (!empty( $sessionNb)) {
           
            for ($i = 0; $i < $sessionNb; $i++) {
                $nb_row[$i] = $i;
            }
        }
        
        //on cree la methode qui permettra d'enregistrer les infos du post dans la bd
        function insert_into_db($data, ManagerRegistry $end)
        {
            foreach ($data as $key => $value) {
                $k[] = $key;
                $v[] = $value;
            }
            $k = implode(",", $k);
            $v = implode(",", $v);
           
            $famille = new Famille();
            // $famille->setUser($user);
            $famille->setNom(ucfirst($data['famille']));
            $manager = $end->getManager();
            $manager->persist($famille);
            $manager->flush();
        }

        //si on clic sur le boutton enregistrer et que les champs du post ne sont pas vide
        if (isset($_POST['enregistrer'])) {
           
            //dd($session_nb_row);
            for ($i = 0; $i < $sessionNb; $i++) {
                
                $data = array(
                    'famille' => $_POST['famille' . $i]
                );
               
                insert_into_db($data, $end);
            }

            // return $this->redirectToRoute('niveaux_index');
        }

        return $this->render('famille/index.html.twig', [
            'nb_rows' => $nb_row,
            'familles' => $familleRepository->findAll(),
            // 'famillesNb' => $familleRepository->count([
            //     'user' => $user
            // ]),
        ]);
    }

    /**
     * @Route("{id}", name="famille_show", methods={"GET"})
     */
    public function show(Famille $famille): Response
    {
        return $this->render('famille/show.html.twig', [
            'famille' => $famille,
        ]);
    }

    /**
     * @Route("{id}_edit", name="famille_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Famille $famille, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FamilleType::class, $famille);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('famille_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('famille/edit.html.twig', [
            'famille' => $famille,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="famille_delete", methods={"POST"})
     */
    public function delete(Request $request, Famille $famille, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$famille->getId(), $request->request->get('_token'))) {
            $entityManager->remove($famille);
            $entityManager->flush();
        }

        return $this->redirectToRoute('famille_index', [], Response::HTTP_SEE_OTHER);
    }
}
