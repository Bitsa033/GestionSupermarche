<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Service\Service;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Connection;

class CategorieController extends AbstractController
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
        $this->initialiserCategories();
    }

    private function initialiserCategories(): void
    {
        // Vérifier si la table categorie est vide
        $count = $this->connection->fetchOne('SELECT COUNT(*) FROM categorie');

        if ($count == 0) {
            // Récupérer les familles existantes
            $familles = $this->connection->fetchAllAssociative('SELECT * FROM famille');

            // Définir les catégories par famille
            $categories = [
                'Produits finis' => ['Ordinateurs', 'Téléphones', 'Accessoires'],
                'Matières premières' => ['Bois', 'Acier', 'Composants électroniques'],
                'Produits en cours' => ['Assemblage partiel', 'Pré-montage'],
                'Fournitures consommables' => ['Emballages', 'Visserie']
            ];

            foreach ($familles as $famille) {
                if (isset($categories[$famille['nomfam']])) {
                    foreach ($categories[$famille['nomfam']] as $categorie) {
                        $this->connection->insert('categorie', [
                            'nom_cat' => $categorie,
                            'famille_id' => $famille['id']
                        ]);
                    }
                }
            }
        }
    }

    /**
     * @Route("categorie_nb", name="categorie_nb")
     */
    public function nb(SessionInterface $session, Request $request)
    {
        if (!empty($request->request->get('nb_row'))) {
            $nb_of_row = $request->request->get('nb_row');
            $session->set('nb_row', $nb_of_row);
        }
        return $this->redirectToRoute('categorie_index');
    }

    /**
     * @Route("categories", name="categorie_index")
     */
    public function categorie(SessionInterface $session, Service $service)
    {
        $user = $this->getUser();

        $sessionLigne = $session->get('nb_row', 1);
        $nb_row = range(0, $sessionLigne - 1);

        if (isset($_POST['enregistrer'])) {
            
            for ($i = 0; $i < $sessionLigne; $i++) {
                $data = [
                    'categorie' => $_POST['categorie' . $i],
                    'famille_id' => $_POST['famille_id' . $i] // il faudra que ton formulaire envoie la famille choisie
                ];
                $service->new_categorie($data);
            }
        }
        
        return $this->render('categorie/index.html.twig', [
            'nb_rows' => $nb_row,
            'categories' => $service->repo_categorie->findAll(),
            'familles'=>$service->repo_famille->findAll()
        ]);
    }

    /**
     * @Route("categorie_{id}", name="categorie_show", methods={"GET"})
     */
    public function show(Categorie $categorie): Response
    {
        return $this->render('categorie/show.html.twig', [
            'categorie' => $categorie,
        ]);
    }

    /**
     * @Route("categorie_{id}_delete", name="categorie_delete", methods={"POST"})
     */
    public function delete(Request $request, Categorie $categorie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $categorie->getId(), $request->request->get('_token'))) {
            $entityManager->remove($categorie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('categorie_index', [], Response::HTTP_SEE_OTHER);
    }
}
