<?php

namespace App\Controller;

use App\Service\Service;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReceptionController extends AbstractController
{
    /**
     * @Route("/reception", name="app_reception")
     */
    public function index(): Response
    {
        return $this->render('reception/index.html.twig', [
            'controller_name' => 'ReceptionController',
        ]);
    }

    /**
     * on valide la reception de l'achat
     * @Route("achat_reception_{id}", name="achat_reception", methods={"GET","POST"})
     */
    public function reception_achat(Service $service, $id, Request $request): Response
    {
        $achat = $service->repo_achat->find($id);
        $qte_achat = (!empty($achat))? $achat->getQte() :dd($achat,'Ce produit n/esxiste pas dans notre base de données !');
        $qte_reception = $service->repo_reception->sommeQteRecue($id);
    
        //si on clic sur le boutton enregistrer et que les champs du post ne sont pas vide
        if (isset($_POST['btn_valider_achat'])) {
            $check_array = $_POST['mag_id'];
            foreach ($_POST['mag_name'] as $key => $value) {
                if (in_array($_POST['mag_name'][$key], $check_array)) {
                    $magasin = $_POST['mag_id'][$key];

                    $prix_unit_achat = $service->repo_achat->find($achat)->getProduit()->getPrixAchat();
                    $prix_unit_vente = $service->repo_achat->find($achat)->getProduit()->getPrixVente();
                    $quantite_tot_achat = $_POST["quantite"][$key];
                    $emballage = ($achat->getProduit()->getUniteAchat() == 
                    $achat->getProduit()->getUniteVente()) ? 1 : 0;
                    
                    if (!empty($_POST["quantiteInit"][$key])) {
                        $quantite_unit_val = $_POST["quantiteInit"][$key];
                    }
                    else {
                        if ($emballage == 1) {
                            # code...
                            $quantite_unit_val=1;
                        } elseif($emballage == 0) {
                            # code...
                            $quantite_unit_val =$service->repo_reception->qteUnitVal($id);
                        }
                        
                    }
                    $quantite_tot_val= floatval($quantite_unit_val) * floatval($quantite_tot_achat);
                    $prix_tot_achat = $prix_unit_achat * floatval($quantite_tot_achat);
                    $prix_tot_val=$prix_unit_vente * $quantite_tot_val;

                    $data = array(
                        'magasin'=>$magasin,
                        'achat' => $achat,
                        'quantite' => $quantite_tot_achat,
                        'prixTotal' => $prix_tot_achat,
                        'qteUnitVal'=>$quantite_unit_val,
                        'qteTotVal'=>$quantite_tot_val,
                        'prixTotVal'=>$prix_tot_achat
                    );

                    $service->new_reception($data);
                    $service->repo_produit->nouveau_stock($id);
                    //dd($quantite_tot_achat);

                    //on enregistre les données dans la bd
                    // if ($achat < $quantite_tot_achat) {
                    //     dd('La quantité reçue ne doit pas dépasser celle commandée');
                    // } elseif($achat == $quantite_tot_achat) {
                    //     # code...
                    //     dd('La quantité reçue est égale à celle commandée');
                    // }
                    
                    
                }
            }
            
            return $this->redirectToRoute('achat_reception', [
                'id' => $achat->getId()
            ]);
        }

        return $this->render('reception/new.html.twig', [
            'achat' => $achat,
            'emballage'=>$emballage = ($achat->getProduit()->getUniteAchat() ==
            $achat->getProduit()->getUniteVente()) ? 1 : 0 ,
            'quantite_unit_val_on_bd'=>$quantite_unit_val_on_bd = 
            ($service->repo_reception->qteUnitVal($id) >0) ? 1 : 0 ,
            'qte_achat' => $qte_achat,
            'qte_reception' => $qte_reception,
            'magasins'=>$service->repo_capacite_magasin->findAll()
        ]);
    }
}
