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
        $qte_achat = (!empty($achat)) ? $achat->getQte() : dd($achat, 'Ce produit n/esxiste pas dans notre base de données !');
        $qte_reception = $service->repo_reception->sommeQteRecue($id);

        //si on clic sur le boutton enregistrer et que les champs du post ne sont pas vide
        if (isset($_POST['btn_valider_achat'])) {

            // Tableaux associatifs clés = magId
            $selected   = $_POST['mag_select'] ?? [];   // ex: [12 => "1", 27 => "1"]
            $quantites  = $_POST['quantite']  ?? [];    // ex: [12 => "5", 27 => "3"]
            $qteInit    = isset($_POST['quantiteInit']) ? (float)$_POST['quantiteInit'] : null; // global (optionnel)

            if (empty($selected)) {
                $this->addFlash('danger', 'Veuillez sélectionner au moins un magasin.');
                // return $this->redirectToRoute(...); // si besoin
            }

            $produit        = $achat->getProduit();              // si $achat est injecté (ParamConverter)
            $prixUnitAchat  = (float)$produit->getPrixAchat();
            $prixUnitVente  = (float)$produit->getPrixVente();
            $emballage      = ($produit->getUniteAchat() === $produit->getUniteVente()) ? 1 : 0;

            foreach ($selected as $magId => $on) {
                // Récupère la quantité correspondant **au même magId**
                $qteTotAchat = isset($quantites[$magId]) ? (float)$quantites[$magId] : 0.0;
                if ($qteTotAchat <= 0) {
                    continue;
                }

                // Détermine la conversion unité -> valeur
                if ($emballage === 1) {
                    $qteUnitVal = 1.0;
                } else {
                    if ($qteInit !== null) {
                        $qteUnitVal = $qteInit; // champ global
                    } else {
                        // fallback: va chercher la conversion déjà connue
                        $qteUnitVal = (float)$service->repo_reception->qteUnitVal($produit->getId());
                        if ($qteUnitVal <= 0) {
                            $qteUnitVal = 1.0;
                        } // garde-fou
                    }
                }

                // Calculs
                $qteTotVal     = $qteUnitVal * $qteTotAchat;
                $prixTotAchat  = $prixUnitAchat * $qteTotAchat;
                $prixTotVal    = $prixUnitVente * $qteTotVal;

                // Prépare les données pour ton service
                $data = [
                    'magasin'   => (int)$magId,
                    'achat'     => $achat->getId(),   // ou l’entité selon la signature de new_reception()
                    'quantite'  => $qteTotAchat,
                    'prixTotal' => $prixTotAchat,
                    'qteUnitVal' => $qteUnitVal,
                    'qteTotVal' => $qteTotVal,
                    'prixTotVal' => $prixTotVal
                ];

                // Enregistre la réception (logique "par date" déjà implémentée)
                $service->new_reception($data);

                // Mets à jour le stock du produit
                $service->repo_produit->nouveau_stock($produit->getId());
            }
            return $this->redirectToRoute('achat_reception', [
                'id' => $achat->getId()
            ]);
        }

        return $this->render('reception/new.html.twig', [
            'achat' => $achat,
            'emballage' => $emballage = ($achat->getProduit()->getUniteAchat() ==
                $achat->getProduit()->getUniteVente()) ? 1 : 0,
            'quantite_unit_val_on_bd' => $quantite_unit_val_on_bd =
                ($service->repo_reception->qteUnitVal($id) > 0) ? 1 : 0,
            'qte_achat' => $qte_achat,
            'qte_reception' => $qte_reception,
            'magasins' => $service->repo_magasin->findAll()
        ]);
    }
}
