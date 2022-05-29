<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Stock;
use App\Repository\ProduitRepository;
use App\Form\StockType;
use App\Repository\StockRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("stock")
 */
class StockController extends AbstractController
{
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
        return $this->redirectToRoute('produit_index');
    }

    /**
     * Insertion et affichage des filieres
     * @Route("index_{id}", name="stock_index")
     */
    public function stock(SessionInterface $session, StockRepository $stockRepository,Produit $produit,ProduitRepository $produitRepository, Request $request, ManagerRegistry $end)
    {
        //$getIdFamille=$session->get('famille',[]);
        //$repoFam=$familleRepository->find($getIdFamille);
        // if (empty($getIdFamille)) {
        //     $session->set('famille',$famille);
        // }
        //$session->set('famille',$famille);
        //on cherche l'utilisateur connecté
        $user = $this->getUser();
        //si l'utilisateur est n'est pas connecté,
        // on le redirige vers la page de connexion
        // if (!$user) {
        //     return $this->redirectToRoute('app_login');
        // }
        //on recupere la valeur du nb_row stocker dans la session
        $sessionNb = $session->get('nb_row', []);
        //on cree un tableau qui permettra de generer plusieurs champs dans le post
        //en fonction de la valeur de nb_row
        $nb_row = array(1);
        //pour chaque valeur du compteur i, on ajoutera un champs de plus en consirerant que 
        //nb_row par defaut=1
        if (!empty( $sessionNb)) {
           
            for ($i = 0; $i < $sessionNb; $i++) {
                $nb_row[$i] = $i;
            }
        }
        $session_nb_row=1;
        //on cree la methode qui permettra d'enregistrer les infos du post dans la bd
        function insert_into_db($data,Produit $produit,ProduitRepository $produitRepository, ManagerRegistry $end,$user)
        {
            foreach ($data as $key => $value) {
                $k[] = $key;
                $v[] = $value;
            }
            $k = implode(",", $k);
            $v = implode(",", $v);
            //echo $data['filiere'];
            $getProduit=$produitRepository->find($produit);
            
            $stock = new Stock();
            $stock->setProduit($produit);
            $stock->setQt($data['Qt']);
            $stock->setQd($data['Qd']);
            $stock->setQs($data['Qs']);
            $stock->setPau($data['Pau']);
            $stock->setPat($data['Pat']);
            $stock->setPvu($data['Pvu']);
            $stock->setPvt($data['Pvt']);
            $stock->setBvu($data['Bvu']);
            $stock->setBvt($data['Bvt']);
            // $stock->setRef(strtoupper($data['ref']));
            $stock->setCreatedAt(new \datetime);
            $manager = $end->getManager();
            $manager->persist($stock);
            $manager->flush();
        }

        //si on clic sur le boutton enregistrer et que les champs du post ne sont pas vide
        if (isset($_POST['enregistrer'])) {
            $session_nb_row = $session->get('nb_row', []);
            //dd($session_nb_row);
            for ($i = 0; $i < $session_nb_row; $i++) {
                // $ref=rand(001,5599);
                //on recupere le stock total du post
                $st=$_POST['qtet' . $i];
                //on recupere le prix d'achat unitaire du stock obtenu du post
                $pau=$_POST['prixt' . $i];
                //on initialise un diviseur($d)
                $d=4;
                //on calcul le stock de securite(soit 1/4 du stock total)
                $ss=floor($st / $d);
                //on calcul le stock disponible(soit 3/4 du stock total)
                $sd=$st - $ss;
                //on calcul le prix d'achat total
                $pat=ceil($pau * $st);
                //on calcul le prix de vente unitaire
                $pvu=ceil($pau / $d + $pau);
                //on calcul le prix de vente total
                $pvt=$pvu * $st;
                //on calcul le benefice de vente unitaire
                $bvu=$pvu - $pau;
                //on calcul le benefice de vente total
                $bvt=$bvu * $st;
                //on stocke toutes les donnees dans le tableau
                $data = array(
                    'Qt' => $st,
                    'Qd'=>$sd ,
                    'Qs'=>$ss,
                    'Pau'=>$pau,
                    'Pat' => $pat,
                    'Pvu'=>$pvu,
                    'Pvt'=>$pvt,
                    'Bvu'=>$bvu,
                    'Bvt'=>$bvt
                );
               
                insert_into_db($data,$produit,$produitRepository ,$end,$user);
            }

            // return $this->redirectToRoute('niveaux_index');
        }

        return $this->render('stock/index.html.twig', [
            'nb_rows' => $nb_row,
            'produit'=>$produit,
            'stocks'=>$stockRepository->findAll(),
            // 'famillesNb' => $familleRepository->count([
            //     'user' => $user
            // ]),
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
