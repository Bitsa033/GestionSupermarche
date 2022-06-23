<?php

namespace App\Controller;

use App\Entity\Catuval;
use App\Entity\Margeprix;
use App\Entity\Produit;
use App\Entity\Stock;
use App\Entity\Uval;
use App\Repository\ProduitRepository;
use App\Form\StockType;
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
    public function listeProfits(ProduitRepository $produitRepository){

        return $this->render('stock/profit.html.twig',[
            'stocks'=>$produitRepository->ListeProfits()
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
     * @Route("sessionDevis", name="stock_sessionDevis")
     */
    public function sessionDevis(SessionInterface $session, Request $request)
    {
        if (!empty($request->request->get('budjet')) && !empty($request->request->get('prixUnitArt'))) {
            $budjet=$request->request->get('budjet');
            //$x=str_replace($budjet,$budjet,$budjet);
            $prixUnitArt=$request->request->get('prixUnitArt');
            $nbArt=floor($budjet / $prixUnitArt);
            $depenses=$nbArt *  $prixUnitArt;
            $resteBudjet=$budjet - $depenses;
        }
        else{
           
        }

        $get_budjet = $session->get('budjet', []);
        if (!empty($get_budjet)) {
            $session->set('budjet', $budjet);
            $session->set('prixUnitArt', $prixUnitArt);
            $session->set('depenses', $depenses);
            $session->set('nbArt', $nbArt);
            $session->set('resteBudjet', $resteBudjet);
        }
        $session->set('budjet', $budjet);
        $session->set('prixUnitArt', $prixUnitArt);
        $session->set('depenses', $depenses);
        $session->set('nbArt', $nbArt);
        $session->set('resteBudjet', $resteBudjet);
        //dd($session);

        return $this->redirectToRoute('stock_new');
    }

    /**
     * @Route("sessionProduit", name="stock_sessionProduit")
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
        return $this->redirectToRoute('stock_new');
    }

    /**
     * @Route("sessionUval", name="stock_Uval")
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
    public function stock(UvalRepository $uvalRepository,MargeprixRepository $margeprixRepository,SessionInterface $session,AchatRepository $achatRepository, ManagerRegistry $end)
    {
        //on cherche l'utilisateur connecté
        $user = $this->getUser();
        $sessionProduit=$session->get('produit',[]);
        $sessionUval=$session->get('uval',[]);
        $sessionUvalVente=$session->get('uvalVente',[]);
        
        //on recupere la marge des prix
        $idMarge=$margeprixRepository->find(1);
        $margeFamille=$idMarge->getMarge();
        if (!empty($session->get('nb_row', []))) {
            $sessionLigne = $session->get('nb_row', []);
        }
        else{
            $sessionLigne = 1;
        }
        $sessionNb = $sessionLigne/$sessionLigne;
        $sessionBudjet=$session->get('budjet',[]);
        $sessionPrixUnitArt=$session->get('prixUnitArt',[]);
        $sessionNbArt=$session->get('nbArt',[]);
        $sessionDepenses=$session->get('depenses',[]);
        $sessionResteBudjet=$session->get('resteBudjet',[]);
        
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
        function insert_into_db($data,$idProduit,AchatRepository $achatRepository,$idUvalStock,$idUvalVente,UvalRepository $uvalRepository, ManagerRegistry $end)
        {
            foreach ($data as $key => $value) {
                $k[] = $key;
                $v[] = $value;
            }
            $k = implode(",", $k);
            $v = implode(",", $v);
           
            $achat=$achatRepository->find($idProduit);
            $uniteStockage=$uvalRepository->find($idUvalStock);
            $uniteVente=$uvalRepository->find($idUvalVente);
            
            $stock = new Stock();
            $stock->setAchat($achat);
            $stock->setQteStockage($data['quantiteStockage']);
            $stock->setPrixVenteUnitaireStock($data['prixVenteUnitaireStock']);
            $stock->setPrixVenteTotaleStock($data['prixVenteTotaleStock']);
            $stock->setProfitUnitaireStock($data['profitUnitaireStock']);
            $stock->setProfitTotalStock($data['profitTotalStock']);
            $stock->setUniteStockage($uniteStockage);
            $stock->setQteGenValStock(1);
            $stock->setC("=");
            $stock->setQteGenUnite($data['qteGenUnite']);
            $stock->setUniteVenteStock($uniteVente);
            $stock->setQteTotaleUnite($data['qteTotaleUnite']);
            $stock->setPrixUniteVenteStock($data['prixUniteVenteStock']);
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
                    'qteGenUnite'=>0,
                    'qteTotaleUnite'=>0,
                    'prixUniteVenteStock'=>0
                );
               
                insert_into_db($data,$sessionProduit,$achatRepository,$sessionUval,$sessionUvalVente,$uvalRepository ,$end);
            }

        }

        //repository de la classe Catuval
        $repoCatuval=$this->getDoctrine()->getRepository(Catuval::class);
        // devis d'achat pour le stock[]
        if (!empty($sessionBudjet) && !empty($sessionPrixUnitArt)) {
            $budjet=$sessionBudjet;
            $prixUnitArt=$sessionPrixUnitArt;
            $nbArt=$sessionNbArt;
            $depenses=$sessionDepenses;
            $resteBudjet=$sessionResteBudjet;
        }
        else{
            $budjet='XXX ...';
            $prixUnitArt='XXX ...';
            $nbArt='XXX ...';
            $depenses='XXX ...';
            $resteBudjet='XXX ...';
        }

        return $this->render('stock/new.html.twig', [
            'nb_rows' => $nb_row,
            'achats'=>$achatRepository->findAll(),
            'catuvals'=>$repoCatuval->findAll(),
            'uvals' => $uvalRepository->findBy([
                'catuval' => 1
            ]),
            'budjet'=>$budjet,
            'prixUnit'=>$prixUnitArt,
            'nbArt'=>$nbArt,
            'depenses'=>$depenses,
            'resteBudjet'=>$resteBudjet
        ]);
    }

    /**
     * @Route("/{id}", name="stock_show", methods={"GET"})
     */
    public function show(Stock $stock): Response
    {
        return $this->render('stock/show.html.twig', [
            'stock' => $stock,
        ]);
    }

    /**
     * @Route("{id}_edit", name="stock_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Stock $stock, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(StockType::class, $stock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('stock_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('stock/edit.html.twig', [
            'stock' => $stock,
            'form' => $form->createView(),
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
