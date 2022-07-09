<?php

namespace App\Controller;

use App\Entity\Achat;
use App\Entity\Catuval;
use App\Form\AchatType;
use App\Repository\AchatRepository;
use App\Repository\ProduitRepository;
use App\Repository\UvalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("achat_")
 */
class AchatController extends AbstractController
{
    /**
     * @Route("index", name="achat_index", methods={"GET"})
     */
    public function index(AchatRepository $achatRepository): Response
    {
        return $this->render('achat/achat.html.twig', [
            'achats' => $achatRepository->findAll(),
        ]);
    }

    /**
     * @Route("nb", name="achat_nb")
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
     * @Route("sessionDevis", name="achat_sessionDevis")
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
     * @Route("sessionProduit", name="achat_sessionProduit")
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
     * @Route("sessionUval", name="achat_Uval")
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
     * @Route("new", name="achat_new")
     */
    public function new(UvalRepository $uvalRepository,SessionInterface $session,ProduitRepository $produitRepository,Request $request, ManagerRegistry $end)
    {
         //on cherche l'utilisateur connecté
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
         //on cree la methode qui permettra d'enregistrer les infos du post dans la bd
         function insert_into_db($data,$idProduit,ProduitRepository $produitRepository,$idUval,UvalRepository $uvalRepository, ManagerRegistry $end)
         {
             foreach ($data as $key => $value) {
                 $k[] = $key;
                 $v[] = $value;
             }
             $k = implode(",", $k);
             $v = implode(",", $v);
            
             $produit=$produitRepository->find($idProduit);
             $uniteAchat=$uvalRepository->find($idUval);
         
             $achat = new Achat();
             $achat->setProduit($produit);
             $achat->setUniteAchat($uniteAchat);
             $achat->setQteAchat($data['quantiteAchat']);
             $achat->setPrixAchatUnitaire($data['prixAchatUnitaire']);
             $achat->setPrixAchatTotal($data['prixAchatTotal']);
             $achat->setDateachat(new \DateTime());
             // $stock->setRef(strtoupper($data['ref']));
             $manager = $end->getManager();
             $manager->persist($achat);
             $manager->flush();
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
                
                 insert_into_db($data,$sessionProduit,$produitRepository,$sessionUval,$uvalRepository ,$end);
             }
 
         }
 
         // devis d'achat pour le stock[]
         if (!empty($sessionNbArt) && !empty($sessionPrixUnitArt)) {
             $nbArt=$sessionNbArt;
             $prixUnitArt=$sessionPrixUnitArt; 
             $prixTotalArt=$prixTotalArt;
         }
         else{
             $nbArt='XXX ...';
             $prixUnitArt='XXX ...';
             $prixTotalArt='XXX ...';
         }
 
         return $this->render('achat/new.html.twig', [
             'nb_rows' => $nb_row,
             'produits'=>$produitRepository->findAll(),
             'uvals' => $uvalRepository->findAll(),
             'nbArt'=>$nbArt,
             'prixUnit'=>$prixUnitArt,
             'prixTotal'=>$prixTotalArt,
         ]);
    }

    /**
     * @Route("{id}", name="achat_show", methods={"GET"})
     */
    public function show(Achat $achat): Response
    {
        return $this->render('achat/show.html.twig', [
            'achat' => $achat,
        ]);
    }

    /**
     * @Route("{id}_edit", name="achat_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Achat $achat, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AchatType::class, $achat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('achat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('achat/edit.html.twig', [
            'achat' => $achat,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="achat_delete", methods={"POST"})
     */
    public function delete(Request $request, Achat $achat, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$achat->getId(), $request->request->get('_token'))) {
            $entityManager->remove($achat);
            $entityManager->flush();
        }

        return $this->redirectToRoute('achat_index', [], Response::HTTP_SEE_OTHER);
    }
}
