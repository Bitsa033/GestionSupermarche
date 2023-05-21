<?php

namespace App\Controller;

use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Service;

class StockController extends AbstractController
{
    /**
     * @Route("stock_liste", name="stock_liste")
     */
    public function stock_liste(Service $service){

        return $this->render('stock/stocks.html.twig',[
            'stocks'=>$service->repo_reception->findAll()
        ]);
    }

    /**
     * @Route("stock_profits", name="stock_profits")
     */
    // public function profits(Service $service){
        
    //     return $this->render('stock/profit.html.twig',[
    //         'stocks'=>$service->repo_stockt->findAll(),
    //     ]);
    // }
    
    /**
     * @Route("stock_nb", name="stock_nb")
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
        return $this->redirectToRoute('stock_new');
    }

    /**
     * @Route("stock_sortie", name="stock_sortie")
     */
    public function stock_sortie(Service $service)
    {
        if (!empty($_POST)) {
            $check_id=$_POST['produit_id'];
            $check_name=$_POST['produit_name'];
            foreach ($check_name as $key => $value) {
                # code...
                if (in_array($check_name[$key],$check_id)) {
                    # code...
                    $produit = $check_name[$key];

                    $quantite = $_POST["quantite"][$key];
                    $prixUnitaire = $service->repo_produit->find($produit)->getPrixVente();
                    $prixTotal = $prixUnitaire * floatval($quantite);
                    //$dateCommande = $_POST['date_commande'][$key];

                    $data = array(
                        'produit' => $produit,
                        'quantite' => $quantite,
                        'prixTotal' => $prixTotal,
                        //'dateAchat' => $dateCommande
                    );

                    // on enregistre dans la bd
                    $service->new_sortie($data);
                }
            }
        }

       return $this->render('stock/sortie.html.twig',[
        'produits'  =>$service->repo_produit->stockTotal(),
        'uvals' => $service->repo_uval->findAll(),
       ]);
    }

    /**
     * @Route("stock_calculatrice", name="stock_calculatrice")
     */
    public function stock_calculatrice(Request $request)
    {
        if (!empty($request->request->get('somme')) && !empty($request->request->get('depenses'))) {
           $somme=$request->request->get('somme');
           $depenses=$request->request->get('depenses');
            $resultat= floatval($somme- $depenses);
            return $this->json([
                'resultat'=>$resultat,
                'icon'=>'success',
            ]);
        
       }

    // dd($request);

       //return $this->redirectToRoute('stock_sortie');;
    }

    

    /**
     * @Route("stock_update", name="stock_update")
     */
    // public function updateStock(Service $service,SessionInterface $session)
    // {
    //     $user = $this->getUser(); //on cherche l'utilisateur connecté
    //     $sessionProduit=$session->get('stock',[]);//on recupere l'id de l'achat dans la session [stock]
    //     //optionModifStock
    //     $sessionoptionModifStock=$session->get('optionModifStock',[]);//on recupere l'id  dans la session [optionModifStock]
    //     $sessionMargePrix=$session->get('margePrix',[]);
    //     if (!empty($sessionMargePrix)) {
    //         $idMarge=$service->repo_margeprix->find($sessionMargePrix);//on recupere la marge des prix
    //         $margeFamille=$idMarge->getMarge();
    //     }
    //     else{
    //         $idMarge=null;
    //     }
        
    //     if (!empty($session->get('nb_row', []))) {
    //         $sessionLigne = $session->get('nb_row', []);
    //     }
    //     else{
    //         $sessionLigne = 1;
    //     }
    //     $sessionNb = $sessionLigne/$sessionLigne;
    //     if (!empty($sessionProduit)) {
    //         //on recupere tous les infos du stock de [sessionProduit]
    //         $stock=$service->repo_stockt->find($sessionProduit);
           
    //     }
    //     else{
    //         $stock=null;
    //     }
        
    //     $nb_row = array(1);
    //     //pour chaque valeur du compteur i, on ajoutera un champs de plus en consirerant que nb_row par defaut=1
    //     if (!empty( $sessionNb)) {
    //         for ($i = 0; $i < $sessionNb; $i++) {
    //             $nb_row[$i] = $i;
    //         }
    //     }
        
    //     //si on clic sur le boutton enregistrer et que les champs du post ne sont pas vide
    //     if (isset($_POST['enregistrer'])) {
    //         //dd($session_nb_row);
    //         for ($i = 0; $i < $sessionNb; $i++) {
    //             //on recupere la quantite de stockage
    //             $qteStock=$_POST['qteStock'.$i];
    //             //on recupere le prix unitaire d'achat
    //             $prixUnitAchat=$_POST['prixUnit'.$i];
    //             //on calcule le prix unitaire de vente
    //             $idStock=$service->repo_stockt->find($sessionProduit);
    //             $ancienProfit=$idStock->getProfitUnitaire();
    //             if ($sessionoptionModifStock=='addition') {
    //                 $prixUnitVente=$prixUnitAchat +  $margeFamille;
    //                 //on calcul le profit unitaire
    //                 $profUnit=$ancienProfit+$margeFamille;
    //                 $this->addFlash('success','Modification réussie avec succès !');
    //             }
    //             elseif ($sessionoptionModifStock=='soustraction') {
                   
    //                 if ($margeFamille==$ancienProfit) {
    //                     $this->addFlash('error','Echec de modification car la marge de prix est nulle !');
    //                     return $this->redirectToRoute('stock_update');
    //                 }
    //                 elseif ($margeFamille>$ancienProfit) {
    //                     $this->addFlash('error','Echec de modification car la nouvelle marge de prix est plus
    //                     grande que la récente !');
    //                     return $this->redirectToRoute('stock_update');
    //                 }

    //                 $prixUnitVente=$prixUnitAchat - $margeFamille;
    //                 $profUnit=$ancienProfit-$margeFamille;
    //                 $this->addFlash('success','Modification réussie avec succès !');
    //             }
    //             //on calcul le prix total de vente
    //             $prixTotalVente=$prixUnitVente * $qteStock;
                
    //             //on calcul le profit total
    //             $profTot=$profUnit * $qteStock;
    //             //on stocke toutes les donnees dans le tableau
    //             $data = array(
    //                 'quantiteStockage' =>$qteStock,
    //                 'prixVenteUnitaireStock'=>$prixUnitVente,
    //                 'prixVenteTotaleStock'=>$prixTotalVente,
    //                 'profitUnitaireStock'=>$profUnit,
    //                 'profitTotalStock'=>$profTot,
    //                 // 'qteGenUnite'=>0,
    //                 // 'qteTotaleUnite'=>0,
    //                 // 'prixUniteVenteStock'=>0
    //             );
    //            //on cree le service qui permettra d'enregistrer les infos du post dans la bd
    //             //$service->StockUpdate($data,$stock ,$end);
    //         }

    //     }

    //     return $this->render('stock/edit.html.twig', [
    //         'nb_rows' => $nb_row,
    //         'stocks'=>$service->repo_stockt->findAll(),
    //         'uvals' => $service->repo_uval->findAll(),
    //         'stock'=>$stock,//on affiche toutes les infos de cette variable
    //         'margePrix'=>$service->repo_margeprix->findAll(),//on affiche toutes les marges de prix
    //     ]);
    // }

    /**
     * @Route("stock_delete_{id}", name="stock_delete", methods={"POST"})
     */
    public function delete(Request $request, Produit $stock, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$stock->getId(), $request->request->get('_token'))) {
            $entityManager->remove($stock);
            $entityManager->flush();
        }

        return $this->redirectToRoute('stock_index', [], Response::HTTP_SEE_OTHER);
    }
}
