<?php

namespace App\Controller;

use App\Entity\Catuval;
use App\Entity\Margeprix;
use App\Entity\Produit;
use App\Entity\Stock;
use App\Entity\Uval;
use App\Repository\ProduitRepository;
use App\Form\StockType;
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
            'stocks'=>$stockRepository->ListeStocksSelonFifo()
        ]);
    }

    /**
     * @Route("listeAchat", name="stock_listeAchat")
     */
    public function listeAchat(StockRepository $stockRepository){

        return $this->render('stock/achat.html.twig',[
            'stocks'=>$stockRepository->ListeStocksSelonFifo()
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
     * @Route("devis", name="stock_devis")
     */
    public function devis(StockRepository $stockRepository, Request $request){

        if (!empty($request->request->get('budjet')) && !empty($request->request->get('prixUnitArt'))) {
            $budjet=$request->request->get('budjet');
            $prixUnitArt=$request->request->get('prixUnitArt');
            $nbArt=floor($budjet / $prixUnitArt);
            $depenses=$nbArt *  $prixUnitArt;
            $resteBudjet=$budjet - $depenses;
        }
        else{
            $nbArt='XXX ...';
            $depenses='XXX ...';
            $resteBudjet='XXX ...';
            $budjet='XXX ...';
        }

        return $this->render('stock/devis.html.twig',[
            'stocks'=>$stockRepository->ListeStocksSelonFifo(),
            'budjet'=>$budjet,
            'nbArt'=>$nbArt,
            'depenses'=>$depenses,
            'resteBudjet'=>$resteBudjet
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
        return $this->redirectToRoute('stock_index');
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
            $nbArt='XXX ...';
            $depenses='XXX ...';
            $resteBudjet='XXX ...';
            $budjet='XXX ...';
            $prixUnit='XXX ...';
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

        return $this->redirectToRoute('stock_index');
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
        return $this->redirectToRoute('stock_index');
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
        return $this->redirectToRoute('stock_index');
    }

    /**
     * Insertion et affichage des filieres
     * @Route("index", name="stock_index")
     */
    public function stock(MargeprixRepository $margeprixRepository,SessionInterface $session, StockRepository $stockRepository,ProduitRepository $produitRepository, Request $request, ManagerRegistry $end)
    {
        //on cherche l'utilisateur connecté
        $user = $this->getUser();
        $getIdProduit=$session->get('produit',[]);
        $getSessionUval=$session->get('uval',[]);
        $repoUval=$this->getDoctrine()->getRepository(Uval::class);
        //on recupere la marge des prix
        $margep=$margeprixRepository->find(1);
        $marge=$margep->getMarge();
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
        // $session_nb_row=1;
        //on cree la methode qui permettra d'enregistrer les infos du post dans la bd
        function insert_into_db($data,$getIdProduit,ProduitRepository $produitRepository,$getIdUval,UvalRepository $uvalRepository, ManagerRegistry $end,$user)
        {
            foreach ($data as $key => $value) {
                $k[] = $key;
                $v[] = $value;
            }
            $k = implode(",", $k);
            $v = implode(",", $v);
            //echo $data['filiere'];
            $getProduit=$produitRepository->find($getIdProduit);
            $getUval1=$uvalRepository->find($getIdUval);
            $getUval=$getProduit->getUvalp();
            
            $stock = new Stock();
            $stock->setProduit($getProduit);
            $stock->setQt($data['Qt']);
            $stock->setQs($data['Qs']);
            $stock->setPau($data['Pau']);
            $stock->setPat($data['Pat']);
            $stock->setPvu($data['Pvu']);
            $stock->setPvt($data['Pvt']);
            $stock->setBvu($data['Bvu']);
            $stock->setBvt($data['Bvt']);
            $stock->setUvalst($getUval1);
            $stock->setQgc(1);
            $stock->setC("=");
            $stock->setQgv($data['qgv']);
            $stock->setUgv($getUval);
            $stock->setQtv($data['qtv']);
            $stock->setPvuv($data['pvuv']);
            // $stock->setRef(strtoupper($data['ref']));
            $manager = $end->getManager();
            $manager->persist($stock);
            $manager->flush();
        }

        //si on clic sur le boutton enregistrer et que les champs du post ne sont pas vide
        if (isset($_POST['enregistrer'])) {
            // $session_nb_row = $session->get('nb_row', []);
            //dd($session_nb_row);
            for ($i = 0; $i < $sessionNb; $i++) {
                //on recupere la qte total du stock
                $st=$_POST['qtet' . $i];
                //on recupere la qte generale de valorisation du stock
                $qgv=$_POST['qgv'];
                //on recupere le prix d'achat unitaire du stock obtenu du post
                $pau=$_POST['prixu' . $i];
                //on calcul le prix d'achat unitaire du produit
                $pauvs0=ceil($pau/$qgv);
                //on calcul le prix de vente unitaire du produit
                $pvuvs0=ceil($pauvs0 + $marge);
                //on initialise un diviseur($d)
                $d=4;
                //on calcul le stock de securite(soit 1/4 du stock total)
                $ss=floor($st / $d);
                //on calcul le prix d'achat total du stock
                $pat=ceil($pau * $st);
                //on calcul le prix de vente unitaire/unite de stock
                $pvu=ceil($pvuvs0 * $qgv);
                //on calcul le prix de vente total du stock
                $pvt=ceil($pvu * $st);
                //on calcul le benefice de vente unitaire du stock
                $bvu=ceil($pvu - $pau);
                //on calcul le benefice de vente total du stock
                $bvt=ceil($bvu * $st);
                
                //qte totale de valorisation du produit
                $qtv=$qgv * $st;
                
                //on stocke toutes les donnees dans le tableau
                $data = array(
                    'Qt' => $st,
                    'Qs'=>$ss,
                    'Pau'=>$pau,
                    'Pat' => $pat,
                    'Pvu'=>$pvu,
                    'Pvt'=>$pvt,
                    'Bvu'=>$bvu,
                    'Bvt'=>$bvt,
                    'qgv'=>$qgv,
                    'qtv'=>$qtv,
                    'pvuv'=>$pvuvs0
                );
               
                insert_into_db($data,$getIdProduit,$produitRepository,$getSessionUval,$repoUval ,$end,$user);
            }

        }

        //affichage de l'unite de vente du produit
        if (!empty($getIdProduit)) {
            $uvp=$produitRepository->find($getIdProduit);
        }
        else{
            $uvp='';
        }
        //affichage de l'unite de stockage du produit
        if (!empty($getSessionUval)) {
            $uvs=$repoUval->find($getSessionUval);
        }
        else{
            $uvs='';
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

        return $this->render('stock/index.html.twig', [
            'nb_rows' => $nb_row,
            'stocks'=>$stockRepository->findAll(),
            'produits'=>$produitRepository->findAll(),
            'catuvals'=>$repoCatuval->findAll(),
            'uvalprod'=>$uvp,
            'uvs'=>$uvs,
            'uvals' => $repoUval->findBy([
                'catuval' => 2
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
