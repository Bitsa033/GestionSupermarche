<?php

namespace App\Controller;

use App\Service\Service;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class AchatController extends AbstractController
{

    /**
     * @Route("/", name="achat_liste", methods={"GET"})
     */
    public function achat_liste(Service $service): Response
    {
        
        try {
            $rep= $service->repo_achat->findAll();
        } catch (\Throwable $th) {
            die('Erreur, base de données introuvable, si vous utilisez un logiciel de base de donées,
            veuillez l\'activé !');
        }
        
        return $this->render('achat/achat_liste.html.twig', [
            'achats' => $rep
        ]);
    }

    /**
     * @Route("achat_nb", name="achat_nb")
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
        } else {
            $session->set('nb_row', 1);
            //dd($session);
        }
        return $this->redirectToRoute('achat_new');
    }

    /**
     * @Route("achat_new", name="achat_new")
     */
    public function new(Service $service)
    {
        //on cherche l'utilisateur connecté
        $user = $this->getUser();

        //si on clic sur le boutton enregistrer et que les champs du post ne sont pas vide
        if (isset($_POST['enregistrer'])) {
            //dd($session_nb_row);
            $check_array = $_POST['produit_id'];
            foreach ($_POST['produit_name'] as $key => $value) {
                if (in_array($_POST['produit_name'][$key], $check_array)) {
                    $produit = $_POST['produit_name'][$key];

                    $quantite = $_POST["quantite"][$key];
                    $prixUnitaire = $service->repo_produit->find($produit)->getPrixAchat();
                    $prixTotal = $prixUnitaire * floatval($quantite);
                    $dateCommande = $_POST['date_commande'][$key];

                    $data = array(
                        'user' => $user,
                        'produit' => $produit,
                        'quantite' => $quantite,
                        'prixTotal' => $prixTotal,
                        'dateAchat' => $dateCommande
                    );

                    //on enregistre les données dans la bd
                    $service->new_achat($data);
                }
            }
        }

        return $this->render('achat/new.html.twig', [
            'produits' => $service->repo_produit->findAll(),
            'familles'=>$service->repo_famille->findAll(),
            'uvals' => $service->repo_uval->findAll(),
        ]);
    }

    /**
     * @Route("achat_edit_{id}", name="achat_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Service $service): Response
    {
        $form = $this->createForm(AchatType::class, $service->table_achat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $service->db->flush();

            return $this->redirectToRoute('achat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('achat/edit.html.twig', [
            'achat' => $service->table_achat,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("achat_delete_{id}", name="achat_delete", methods={"POST"})
     */
    public function delete(Request $request, Service $service, $id): Response
    {
        $achat = $service->repo_achat->find($id);
        if ($this->isCsrfTokenValid('delete', $achat, $request->request->get('_token'))) {
            $service->repo_achat->remove($achat);
            $service->db->flush();
        }

        return $this->redirectToRoute('achat_index', [], Response::HTTP_SEE_OTHER);
    }
}
