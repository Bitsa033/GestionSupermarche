<?php

namespace App\Controller;

use App\Entity\Stock;
use App\Repository\AchatRepository;
use App\Repository\MargeprixRepository;
use App\Repository\UvalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Service;

class StockController extends AbstractController
{
    /**
     * @Route("stock_listeSt", name="stock_listeSt")
     */
    public function listeSt(Service $service){

        return $this->render('stock/stocks.html.twig',[
            'stocks'=>$service->repo_stockt->findAll()
        ]);
    }

    /**
     * @Route("stock_listeProfits", name="stock_listeProfits")
     */
    public function listeProfits(Service $service){
        
        return $this->render('stock/profit.html.twig',[
            'stocks'=>$service->repo_stockt->findAll(),
        ]);
    }
    
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
     * @Route("stock_sessionAchat", name="stock_sessionAchat")
     */
    public function sessionProduit(SessionInterface $session, Request $request)
    {
        if (!empty($request->request->get('produit'))) {
            $achat = $request->request->get('produit');
            $get_achat = $session->get('produit', []);
            if (!empty($get_achat)) {
                $session->set('achat', $achat);
            }
            $session->set('achat', $achat);
            // dd($session);
        }
        return $this->redirectToRoute('stock_new');
    }

     /**
     * @Route("stock_sessionStock", name="stock_sessionStock")
     */
    public function sessionMargePrix(SessionInterface $session, Request $request)
    {
        if (!empty($request->request->get('margeprix'))) {
            $margeprix = $request->request->get('margeprix');
            $get_margeprix = $session->get('margePrix', []);
            if (!empty($get_margeprix)) {
                $session->set('margePrix', $margeprix);
            }
            $session->set('margePrix', $margeprix);
            //dd($session);
        }

        elseif (!empty($request->request->get('optionModifStock'))) {
            $optionModifStock = $request->request->get('optionModifStock');
            $get_optionModifStock = $session->get('optionModifStock', []);
            if (!empty($get_optionModifStock)) {
                $session->set('optionModifStock', $optionModifStock);
            }
            $session->set('optionModifStock', $optionModifStock);
            //dd($session);
        }

        elseif (!empty($request->request->get('produit'))) {
            $stock = $request->request->get('produit');
            $get_stock = $session->get('stock', []);
            if (!empty($get_stock)) {
                $session->set('stock', $stock);
            }
            $session->set('stock', $stock);
            // dd($session);
        }
        return $this->redirectToRoute('stock_update');
    }

    /**
     * @Route("stock_Uval", name="stock_Uval")
     */
    public function sessionUval(SessionInterface $session, Request $request,AchatRepository $achatRepository)
    {
        //marge prix
        if (!empty($request->request->get('margeprix'))) {
            $margeprix = $request->request->get('margeprix');
            $get_margeprix = $session->get('margePrix', []);
            if (!empty($get_margeprix)) {
                $session->set('margePrix', $margeprix);
            }
            $session->set('margePrix', $margeprix);
            //dd($session);
        }
        //unite de vente
        elseif (!empty($request->request->get('uvente'))) {
            $uvalVente = $request->request->get('uvente');
            $get_uvalVente = $session->get('uvalVente', []);
            if (!empty($get_uvalVente)) {
                $session->set('uvalVente', $uvalVente);
            }
            $session->set('uvalVente', $uvalVente);
            //dd($session);
        }
        //qte de produit et prix unitaire d'un produit
        elseif (!empty($request->request->get('qtps'))) {//si qtps n'est pas vide
            $sessionProduit=$session->get('achat',[]);//on recupere l'id de l'achat dans la session [achat]
            $achat=$achatRepository->find($sessionProduit);
            $qta=$achat->getQteAchat();
            $qtps = $request->request->get('qtps');//qte totale des produits par stock
            $qts=$qta * $qtps;
            $get_qts = $session->get('qts', []);

            if (!empty($get_qts)) {
                $session->set('qts', $qts);
                $session->set('qtps', $qtps);
            }
            $session->set('qts', $qts);
            $session->set('qtps', $qtps);
            //dd($session);
        }
        return $this->redirectToRoute('stock_new');
    }

    /**
     * @Route("stock_new", name="stock_new")
     */
    public function newStock(Service $service,UvalRepository $uvalRepository,MargeprixRepository $margeprixRepository,SessionInterface $session,AchatRepository $achatRepository, ManagerRegistry $end)
    {
        $user = $this->getUser(); //on cherche l'utilisateur connecté
        $sessionProduit=$session->get('achat',[]);//on recupere l'id de l'achat dans la session [achat]
        $sessionMargePrix=$session->get('margePrix',[]);
        $sessionUvalVente=$session->get('uvalVente',[]);
        $sessionQts=$session->get('qts',[]);
        $sessionQtps=$session->get('qtps',[]);
        $sessionPup=$session->get('pup',[]);
        if (!empty($sessionProduit)) {
            $achat=$achatRepository->find($sessionProduit);//on recupere tous les infos d'achat de [sessionProduit]
            $uniteAchat=$achat->getUniteAchat()->getNomuval();
        }
        else {
            $achat=null;
            $uniteAchat=null;
        }
        if (!empty($sessionUvalVente)) {
            $uval=$uvalRepository->find($sessionUvalVente);
            $uniteVente=$uval->getNomuval();

            if ($uniteAchat==$uniteVente) {
                $qteTotale=$achat->getQteAchat();
                $prixTotal=$achat->getPrixAchatTotal();
                $prixUnitaire=$achat->getPrixAchatUnitaire();
     
     
            }
            elseif($uniteAchat != $uniteVente){
                if (!empty($sessionQts)) {
                    $qteTotale=$sessionQts;
                    $ac= $achat->getPrixAchatUnitaire();
                    $prixUnitaire=$ac / $sessionQtps;
                    $prixTotal=$qteTotale * $prixUnitaire;
                }
                else {
                    $qteTotale=null;
                    $prixTotal=null;
                    $prixUnitaire=null;
                }
            }
        }
        else{
            $uval=null;
            $uniteVente=null;
            $qteTotale=null;
            $prixTotal=null;
            $prixUnitaire=null;
        }
        if (!empty($sessionMargePrix)) {
            $idMarge=$margeprixRepository->find($sessionMargePrix);//on recupere la marge des prix
            $margeFamille=$idMarge->getMarge();
        }
        else{
            $idMarge=null;
        }
        if (!empty($session->get('nb_row', []))) {
            $sessionLigne = $session->get('nb_row', []);
        }
        else{
            $sessionLigne = 1;
        }
        $sessionNb = $sessionLigne/$sessionLigne;
        
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
                //on recupere la quantite de stockage
                $qteStock=$_POST['qteStock'.$i];
                //on recupere le prix unitaire d'achat
                $prixUnitAchat=$_POST['prixUnit'.$i];
                //on calcule le prix unitaire de vente
                $prixUnitVente=$prixUnitAchat + $margeFamille;
                //on calcul le prix total de vente
                $prixTotalVente=$prixUnitVente * $qteStock;
                //on recupere le profit unitaire
                $profUnit=$margeFamille;
                //on calcul le profit total
                $profTot=$profUnit * $qteStock;
                //on stocke toutes les donnees dans le tableau
                $data = array(
                    'achat'=>$sessionProduit,
                    'uniteVente'=>$sessionUvalVente,
                    'quantiteStockage' =>$qteStock,
                    'prixVenteUnitaireStock'=>$prixUnitVente,
                    'prixVenteTotaleStock'=>$prixTotalVente,
                    'profitUnitaireStock'=>$profUnit,
                    'profitTotalStock'=>$profTot,
                );
                //on cree le service qui permettra d'enregistrer les infos du post dans la bd
               //$stocker= $service->table_stock;
               
            }

        }

        return $this->render('stock/new.html.twig', [
            'nb_rows' => $nb_row,
            'achats'=>$achatRepository->findAll(),
            'qteTotale'=>$qteTotale,
            'prixTotal'=>$prixTotal,
            'prixUnitaire'=>$prixUnitaire,
            'uniteVente'=>$uniteVente,
            'uniteAchat'=>$uniteAchat,
            'uvals' => $uvalRepository->findAll(),
            'achat'=>$achat,//on affiche toutes les infos de cette variable
            'margePrix'=>$margeprixRepository->findAll()//on affiche toutes les marges de prix
        ]);
    }

    /**
     * @Route("stock_update", name="stock_update")
     */
    public function updateStock(Service $service,SessionInterface $session)
    {
        $user = $this->getUser(); //on cherche l'utilisateur connecté
        $sessionProduit=$session->get('stock',[]);//on recupere l'id de l'achat dans la session [stock]
        //optionModifStock
        $sessionoptionModifStock=$session->get('optionModifStock',[]);//on recupere l'id  dans la session [optionModifStock]
        $sessionMargePrix=$session->get('margePrix',[]);
        if (!empty($sessionMargePrix)) {
            $idMarge=$service->repo_margeprix->find($sessionMargePrix);//on recupere la marge des prix
            $margeFamille=$idMarge->getMarge();
        }
        else{
            $idMarge=null;
        }
        
        if (!empty($session->get('nb_row', []))) {
            $sessionLigne = $session->get('nb_row', []);
        }
        else{
            $sessionLigne = 1;
        }
        $sessionNb = $sessionLigne/$sessionLigne;
        if (!empty($sessionProduit)) {
            //on recupere tous les infos du stock de [sessionProduit]
            $stock=$service->repo_stockt->find($sessionProduit);
           
        }
        else{
            $stock=null;
        }
        
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
                //on recupere la quantite de stockage
                $qteStock=$_POST['qteStock'.$i];
                //on recupere le prix unitaire d'achat
                $prixUnitAchat=$_POST['prixUnit'.$i];
                //on calcule le prix unitaire de vente
                $idStock=$service->repo_stockt->find($sessionProduit);
                $ancienProfit=$idStock->getProfitUnitaireStock();
                if ($sessionoptionModifStock=='addition') {
                    $prixUnitVente=$prixUnitAchat +  $margeFamille;
                    //on calcul le profit unitaire
                    $profUnit=$ancienProfit+$margeFamille;
                    $this->addFlash('success','Modification réussie avec succès !');
                }
                elseif ($sessionoptionModifStock=='soustraction') {
                   
                    if ($margeFamille==$ancienProfit) {
                        $this->addFlash('error','Echec de modification car la marge de prix est nulle !');
                        return $this->redirectToRoute('stock_update');
                    }
                    elseif ($margeFamille>$ancienProfit) {
                        $this->addFlash('error','Echec de modification car la nouvelle marge de prix est plus
                        grande que la récente !');
                        return $this->redirectToRoute('stock_update');
                    }

                    $prixUnitVente=$prixUnitAchat - $margeFamille;
                    $profUnit=$ancienProfit-$margeFamille;
                    $this->addFlash('success','Modification réussie avec succès !');
                }
                //on calcul le prix total de vente
                $prixTotalVente=$prixUnitVente * $qteStock;
                
                //on calcul le profit total
                $profTot=$profUnit * $qteStock;
                //on stocke toutes les donnees dans le tableau
                $data = array(
                    'quantiteStockage' =>$qteStock,
                    'prixVenteUnitaireStock'=>$prixUnitVente,
                    'prixVenteTotaleStock'=>$prixTotalVente,
                    'profitUnitaireStock'=>$profUnit,
                    'profitTotalStock'=>$profTot,
                    // 'qteGenUnite'=>0,
                    // 'qteTotaleUnite'=>0,
                    // 'prixUniteVenteStock'=>0
                );
               //on cree le service qui permettra d'enregistrer les infos du post dans la bd
                //$service->StockUpdate($data,$stock ,$end);
            }

        }

        return $this->render('stock/edit.html.twig', [
            'nb_rows' => $nb_row,
            'stocks'=>$service->repo_stockt->findAll(),
            'uvals' => $service->repo_uval->findAll(),
            'stock'=>$stock,//on affiche toutes les infos de cette variable
            'margePrix'=>$service->repo_margeprix->findAll(),//on affiche toutes les marges de prix
        ]);
    }

    /**
     * @Route("/{id}", name="stock_delete", methods={"POST"})
     */
    public function delete(Request $request, Stock $stock, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$stock->getId(), $request->request->get('_token'))) {
            $entityManager->remove($stock);
            $entityManager->flush();
        }

        return $this->redirectToRoute('stock_index', [], Response::HTTP_SEE_OTHER);
    }
}
