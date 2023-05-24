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
            die('Erreur, base de données introuvable, si vous utilisez un logiciel de base de donées, veuillez l\'activé !');
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
            'uvals' => $service->repo_uval->findAll(),
        ]);
    }

    /**
     * on valide la reception de l'achat
     * @Route("achat_reception_{id}", name="achat_reception", methods={"GET","POST"})
     */
    public function reception_achat(Service $service, $id, Request $request): Response
    {
        $achat = $service->repo_achat->find($id);
        $qte_achat = $achat->getQte();
        $qte_reception = $service->repo_reception->sommeQte($achat);

        //si on clic sur le boutton enregistrer et que les champs du post ne sont pas vide
        if (isset($_POST['btn_valider_achat'])) {
            //dd($session_nb_row);
            // function secure_iterable($var)
            // {
            //     return is_iterable($var) ? $var : array();
            // }

            // foreach (secure_iterable($values) as $value)
            // {
            //     //do stuff...
            // }
            $check_array = $_POST['mag_id'];
            foreach ($_POST['mag_name'] as $key => $value) {
                if (in_array($_POST['mag_name'][$key], $check_array)) {
                    $magasin = $_POST['mag_id'][$key];

                    $prix_unit_achat = $service->repo_achat->find($achat)->getProduit()->getPrixAchat();
                    $prix_unit_vente = $service->repo_achat->find($achat)->getProduit()->getPrixVente();
                    $quantite_tot_achat = $_POST["quantite"][$key];
                    if (!empty($_POST["quantiteInit"][$key])) {
                        $quantite_unit_val = $_POST["quantiteInit"][$key];
                    }
                    else {
                        $quantite_unit_val=1;
                    }
                    $quantite_tot_val= $quantite_unit_val * floatval($quantite_tot_achat);
                    $prix_tot_achat = $prix_unit_achat * floatval($quantite_tot_achat);
                    $prix_tot_val=$prix_unit_vente * $quantite_tot_val;

                    $data = array(
                        'magasin'=>$magasin,
                        'achat' => $achat,
                        'quantite' => $quantite_tot_achat,
                        'prixTotal' => $prix_tot_achat,
                        'quantiteInitVal'=>$quantite_unit_val,
                        'quantiteTotVal'=>$quantite_tot_val,
                        'prixTotVal'=>$prix_tot_val
                    );

                    //on enregistre les données dans la bd
                    $service->new_reception($data);
                    
                }
            }
            
            return $this->redirectToRoute('achat_reception', [
                'id' => $achat->getId()
            ]);
        }

        return $this->render('achat/show.html.twig', [
            'achat' => $achat,
            'qte_achat' => $qte_achat,
            'qte_reception' => $qte_reception,
            'magasins'=>$service->repo_capacite_magasin->findAll()
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
