<?php

namespace App\Controller;

use App\Entity\Stock;
use App\Repository\AchatRepository;
use App\Repository\MargeprixRepository;
use App\Repository\StockRepository;
use App\Repository\UvalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("stock_")
 */
class StockController extends AbstractController
{
    /**
     * @Route("listeSt", name="stock_listeSt")
     */
    public function listeSt(StockRepository $stockRepository){

        return $this->render('stock/stocks.html.twig',[
            'stocks'=>$stockRepository->findAll()
        ]);
    }

    /**
     * @Route("listeProfits", name="stock_listeProfits")
     */
    public function listeProfits(StockRepository $stockRepository){
        
        return $this->render('stock/profit.html.twig',[
            'stocks'=>$stockRepository->findAll(),
        ]);
    }
    
    /**
     * @Route("nb", name="stock_nb")
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
     * @Route("sessionAchat", name="stock_sessionAchat")
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
     * @Route("sessionStock", name="stock_sessionStock")
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
     * @Route("sessionUval", name="stock_Uval")
     */
    public function sessionUval(SessionInterface $session, Request $request)
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
        elseif (!empty($request->request->get('uvalVente'))) {
            $uvalVente = $request->request->get('uvalVente');
            $get_uvalVente = $session->get('uvalVente', []);
            if (!empty($get_uvalVente)) {
                $session->set('uvalVente', $uvalVente);
            }
            $session->set('uvalVente', $uvalVente);
            //dd($session);
        }
        return $this->redirectToRoute('stock_new');
    }

    /**
     * @Route("new", name="stock_new")
     */
    public function newStock(UvalRepository $uvalRepository,MargeprixRepository $margeprixRepository,SessionInterface $session,AchatRepository $achatRepository, ManagerRegistry $end)
    {
        $user = $this->getUser(); //on cherche l'utilisateur connecté
        $sessionProduit=$session->get('achat',[]);//on recupere l'id de l'achat dans la session [achat]
        $sessionMargePrix=$session->get('margePrix',[]);
        // $sessionUvalVente=$session->get('uvalVente',[]);
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
        if (!empty($sessionProduit)) {
            $achat=$achatRepository->find($sessionProduit);//on recupere tous les infos d'achat de [sessionProduit]
           
        }
        else{
            $achat=null;
        }
        
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
        //on cree la methode qui permettra d'enregistrer les infos du post dans la bd
        function insert_into_db($data,$idProduit,AchatRepository $achatRepository, ManagerRegistry $end)
        {
            foreach ($data as $key => $value) {
                $k[] = $key;
                $v[] = $value;
            }
            $k = implode(",", $k);
            $v = implode(",", $v);
           
            $achat=$achatRepository->find($idProduit);
            $uniteAchat=$achatRepository->find($idProduit);
            $uniteStockage=$uniteAchat->getUniteAchat();
            
            $stock = new Stock();
            $stock->setAchat($achat);
            $stock->setQteStockage($data['quantiteStockage']);
            $stock->setPrixVenteUnitaireStock($data['prixVenteUnitaireStock']);
            $stock->setPrixVenteTotaleStock($data['prixVenteTotaleStock']);
            $stock->setProfitUnitaireStock($data['profitUnitaireStock']);
            $stock->setProfitTotalStock($data['profitTotalStock']);
            $stock->setUniteStockage($uniteStockage);
            // $stock->setRef(strtoupper($data['ref']));
            $manager = $end->getManager();
            $manager->persist($stock);
            $manager->flush();
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
                    'quantiteStockage' =>$qteStock,
                    'prixVenteUnitaireStock'=>$prixUnitVente,
                    'prixVenteTotaleStock'=>$prixTotalVente,
                    'profitUnitaireStock'=>$profUnit,
                    'profitTotalStock'=>$profTot,
                );
               
                insert_into_db($data,$sessionProduit,$achatRepository,$end);
            }

        }

        return $this->render('stock/new.html.twig', [
            'nb_rows' => $nb_row,
            'achats'=>$achatRepository->findAll(),
            'uvals' => $uvalRepository->findAll(),
            'achat'=>$achat,//on affiche toutes les infos de cette variable
            'margePrix'=>$margeprixRepository->findAll()//on affiche toutes les marges de prix
        ]);
    }

    /**
     * @Route("update", name="stock_update")
     */
    public function updateStock(UvalRepository $uvalRepository,MargeprixRepository $margeprixRepository,SessionInterface $session,StockRepository $stockRepository, ManagerRegistry $end)
    {
        $user = $this->getUser(); //on cherche l'utilisateur connecté
        $sessionProduit=$session->get('stock',[]);//on recupere l'id de l'achat dans la session [stock]
        //optionModifStock
        $sessionoptionModifStock=$session->get('optionModifStock',[]);//on recupere l'id  dans la session [optionModifStock]

        $sessionMargePrix=$session->get('margePrix',[]);
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
        if (!empty($sessionProduit)) {
            $stock=$stockRepository->find($sessionProduit);//on recupere tous les infos du stock de [sessionProduit]
           
        }
        else{
            $stock=null;
        }
        
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
        //on cree la methode qui permettra d'enregistrer les infos du post dans la bd
        function update_db($data,Stock $stock, ManagerRegistry $end)
        {
            foreach ($data as $key => $value) {
                $k[] = $key;
                $v[] = $value;
            }
            $k = implode(",", $k);
            $v = implode(",", $v);
            
            $stock->setPrixVenteUnitaireStock($data['prixVenteUnitaireStock']);
            $stock->setPrixVenteTotaleStock($data['prixVenteTotaleStock']);
            $stock->setProfitUnitaireStock($data['profitUnitaireStock']);
            $stock->setProfitTotalStock($data['profitTotalStock']);
            $manager = $end->getManager();
            $manager->flush();
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
                $idStock=$stockRepository->find($sessionProduit);
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
               
                update_db($data,$stock ,$end);
            }

        }

        return $this->render('stock/edit.html.twig', [
            'nb_rows' => $nb_row,
            'stocks'=>$stockRepository->findAll(),
            'uvals' => $uvalRepository->findAll(),
            'stock'=>$stock,//on affiche toutes les infos de cette variable
            'margePrix'=>$margeprixRepository->findAll(),//on affiche toutes les marges de prix
            'profit'=>$stockRepository->find($sessionProduit)
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
