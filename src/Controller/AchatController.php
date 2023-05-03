<?php

namespace App\Controller;

use App\Form\AchatType;
use App\Service\Service;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class AchatController extends AbstractController
{
    /**
     * @Route("/", name="achat_index", methods={"GET"})
     */
    public function index(Service $service): Response
    {
        return $this->render('achat/achat.html.twig', [
            'achats' => $service->repo_achat->findAll(),
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
        }
        else {
            $session->set('nb_row', 1);
            //dd($session);
        }
        return $this->redirectToRoute('achat_new');
    }

    /**
     * @Route("achat_sessionDevis", name="achat_sessionDevis")
     */
    public function sessionDevis(SessionInterface $session, Request $request)
    {
        if (!empty($request->request->get('nbArt')) && !empty($request->request->get('prixUnitArt'))) {
            $nbArt=$request->request->get('nbArt');
            
            $prixUnitArt=$request->request->get('prixUnitArt');
            $prixTotalArt=$nbArt *  $prixUnitArt;
        }
        else{
           
        }

        $get_nbArt = $session->get('nbArt', []);
        if (!empty($get_nbArt)) {
            $session->set('nbArt', $nbArt);
            $session->set('prixUnitArt', $prixUnitArt);
            $session->set('prixTotalArt', $prixTotalArt);
        }
        $session->set('nbArt', $nbArt);
        $session->set('prixUnitArt', $prixUnitArt);
        $session->set('prixTotalArt', $prixTotalArt);
        //dd($session);

        return $this->redirectToRoute('achat_new');
    }

     /**
     * @Route("achat_sessionProduit", name="achat_sessionProduit")
     */
    public function sessionProduit(SessionInterface $session, Request $request)
    {
        if (!empty($request->request->get('produit'))) {
            $produit = $request->request->get('produit');
            $get_produit = $session->get('produit', []);
            if (!empty($get_produit)) {
                $session->set('produit', $produit);
            }
            $session->set('produit', $produit);
            // dd($session);
        }
        return $this->redirectToRoute('achat_new');
    }

    /**
     * @Route("achat_Uval", name="achat_Uval")
     */
    public function sessionUval(SessionInterface $session, Request $request)
    {
        if (!empty($request->request->get('uval'))) {
            $uval = $request->request->get('uval');
            $get_uval = $session->get('uval', []);
            if (!empty($get_uval)) {
                $session->set('uval', $uval);
            }
            $session->set('uval', $uval);
            //dd($session);
        }
        return $this->redirectToRoute('achat_new');
    }

    /**
     * @Route("achat_new", name="achat_new")
     */
    public function new(Service $service,SessionInterface $session,Request $request,)
    {
         //on cherche l'utilisateur connecté,le produit, l'unité
         $user = $this->getUser();
         $sessionProduit=$session->get('produit',[]);
         $sessionUval=$session->get('uval',[]);
         
         if (!empty($session->get('nb_row', []))) {
             $sessionLigne = $session->get('nb_row', []);
         }
         else{
             $sessionLigne = 1;
         }
         $sessionNb = $sessionLigne/$sessionLigne;
         $sessionNbArt=$session->get('nbArt',[]);
         $sessionPrixUnitArt=$session->get('prixUnitArt',[]);
         $prixTotalArt=$session->get('prixTotalArt',[]);
         
         //si l'utilisateur est n'est pas connecté, on le redirige vers la page de connexion
         // if (!$user) {
         //     return $this->redirectToRoute('app_login');
         // }
         
         $nb_row = array(1);
         //pour chaque valeur du compteur i, on ajoutera un champs de plus en consirerant que nb_row par defaut=1
         if (!empty( $sessionNb)) {
             for ($i = 0; $i < $sessionNb; $i++) {
                 $nb_row[$i] = $i;
             }
         }
 
         //si on clic sur le boutton enregistrer et que les champs du post ne sont pas vide
         if (isset($_POST['enregistrer'])) {
             //dd($session_nb_row);
             for ($i = 0; $i < $sessionNb; $i++) {
                 $quantiteAchat=$_POST['quantiteAchat'.$i];
                 $prixAchatUnitaire=$_POST['prixAchatUnitaire'.$i];
                 $prixAchatTotal=$_POST['prixAchatTotal'.$i];
                 //on stocke toutes les donnees dans le tableau
                 $data = array(
                     'quantiteAchat'=>$quantiteAchat,
                     'prixAchatUnitaire'=>$prixAchatUnitaire,
                     'prixAchatTotal'=>$prixAchatTotal,
                 );
                
                 //on enregistre les données dans la bd
                 $service->new_achat($data,$sessionProduit,$sessionUval);
             }
 
         }
 
         // devis d'achat pour le stock[]
         if (!empty($sessionNbArt) && !empty($sessionPrixUnitArt)) {
             $nbArt=$sessionNbArt;
             $prixUnitArt=$sessionPrixUnitArt; 
             $prixTotalArt=$prixTotalArt;
         }
         else{
             $nbArt='0000';
             $prixUnitArt='0000';
             $prixTotalArt='0000';
         }

         if (!empty($sessionProduit)) {
             $nom_produit=$service->repo_produit->find($sessionProduit)->getNom();
         }
         else {
            $nom_produit="Aucun produit choisie pour l'instant!";
         }

         if (!empty($sessionUval)) {
            $nom_unite=$service->repo_uval->find($sessionUval)->getNomuval();
        }
        else {
           $nom_unite="Aucune unité choisie pour l'instant!";
        }
         
 
         return $this->render('achat/new.html.twig', [
             'nb_rows' => $nb_row,
             'produits'=>$service->repo_produit->findAll(),
             'uvals' => $service->repo_uval->findAll(),
             'nbArt'=>$nbArt,
             'prixUnit'=>$prixUnitArt,
             'prixTotal'=>$prixTotalArt,
             'nom_produit'=>$nom_produit,
             'nom_unite'=>$nom_unite
         ]);
    }

    /**
     * @Route("achat_{id}", name="achat_show", methods={"GET"})
     */
    public function show(Service $service,$id): Response
    {
        $achat=$service->repo_achat->find($id);
        return $this->render('achat/show.html.twig', [
            'achat' => $achat,
        ]);
    }

    /**
     * @Route("{id}_edit", name="achat_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request,Service $service): Response
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
    public function delete(Request $request,Service $service,$id): Response
    {
        $achat=$service->repo_achat->find($id);
        if ($this->isCsrfTokenValid('delete',$achat, $request->request->get('_token'))) {
            $service->repo_achat->remove($achat);
            $service->db->flush();
        }

        return $this->redirectToRoute('achat_index', [], Response::HTTP_SEE_OTHER);
    }
}
