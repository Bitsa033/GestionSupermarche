<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Service\Service;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{
    /**
     * @Route("produit_nb", name="produit_nb")
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
        return $this->redirectToRoute('produit_new');
    }

    /**
     * @Route("sessionCategorie", name="produit_sessionCategorie")
     */
    public function sessionCategorie(SessionInterface $session, Request $request)
    {
        if (!empty($request->request->get('categorie'))) {
            $categorie = $request->request->get('categorie');
            $get_categorie = $session->get('categorie', []);
            
            if (!empty($get_categorie)) {
                $session->set('categorie', $categorie);
            }
            $session->set('categorie', $categorie);
            // dd($session);
        }
        return $this->redirectToRoute('produit_new');
    }

    /**
     * Insertion et affichage des produits
     * @Route("produit_new", name="produit_new")
     */
    public function produit_new(SessionInterface $session,Service $service)
    {
        $sessionCategorie=$session->get('categorie',[]);
        //on cherche l'utilisateur connecté
        $user = $this->getUser();
        // if (!$user) {
        //     return $this->redirectToRoute('app_login');
        // }
       
        if (!empty($session->get('nb_row', []))) {
            $sessionLigne = $session->get('nb_row', []);
        }
        else{
            $sessionLigne = 1;
        }
        $sessionNb = $sessionLigne;
        $nb_row = array(1);

        if (!empty( $sessionNb)) {
           
            for ($i = 0; $i < $sessionNb; $i++) {
                $nb_row[$i] = $i;
            }
        }

        //si on clic sur le boutton enregistrer et que les champs du post ne sont pas vide
        if (isset($_POST['enregistrer'])) {
            
            //dd($session_nb_row);
            for ($i = 0; $i < $sessionNb; $i++) {
                $ref=rand(0,(1000));
                $data = array(
                    'id_categorie'=>$sessionCategorie,
                    'produit' => $_POST['produit' . $i],
                    'code'    => 'PROD_'.$ref,
                    'uniteAchat' =>$_POST['unite_achat'. $i],
                    'uniteVente'=>$_POST['unite_vente'. $i],
                    'prixAchat'=>$_POST['prix_achat'. $i],
                    'prixVente'=>$_POST['prix_vente'. $i],
                );
               
                $service->new_produit($data);
            }

            // return $this->redirectToRoute('niveaux_index');
        }
        if (!empty($sessionCategorie)) {
            
            $id_categorie=$service->repo_categorie->find($sessionCategorie);
            if ($id_categorie===null) {
                $session->set('categorie',null);
                // return dd($id_famille);
                // $nom_famille="Aucune famille choisie pour l'instant!";
            }
            else {
                $nom_categorie=$service->repo_categorie->find($sessionCategorie)->getNomCat();
            }
        }
        else {
           $nom_categorie ="Aucune categorie choisie pour l'instant!";
        }

        return $this->render('produit/new.html.twig', [
            'nb_rows' => $nb_row,
            'categories'=>$service->repo_categorie->findAll(),
            'produits'=>$service->repo_produit->findAll(),
            'nom_categorie'=>$nom_categorie,
            'unites'=>$service->repo_uval->findAll(),
            'prix'=>$service->repo_margeprix->findAll()
        ]);
    }

    /**
     * @Route("produit_liste", name="produit_liste")
     */
    public function produitsListe(Service $service){

        return $this->render('produit/produits.html.twig',[
            'produits'=>$service->repo_stock->findAll(),
        ]);
    }

    /**
     * @Route("produit_show_{id}", name="produit_show", methods={"GET"})
     */
    public function show(Produit $produit): Response
    {
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    /**
     * @Route("produit_edit_{id}", name="produit_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        

        return $this->render('produit/edit.html.twig', [
            'produit' => $produit,
        ]);
    }

    /**
     * @Route("produit_delete_{id}", name="produit_delete", methods={"POST"})
     */
    public function delete(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
            $entityManager->remove($produit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('produit_index', [], Response::HTTP_SEE_OTHER);
    }
}
