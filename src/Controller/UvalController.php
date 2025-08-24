<?php

namespace App\Controller;

use App\Entity\Emballage;
use App\Entity\Famille;
use App\Entity\Uval;
use App\Service\Service;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Connection;

class UvalController extends AbstractController
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
        $this->initialiserEmballages();
    }

    private function initialiserEmballages(): void
    {
        // Vérifier si la table emballage est vide
        $count = $this->connection->fetchOne('SELECT COUNT(*) FROM uval');

        if ($count == 0) {
            $emballages = [
                'Boîte',
                'Sac plastique',
                'Carton',
                'Film étirable'
            ];

            foreach ($emballages as $emballage) {
                $this->connection->insert('uval', [
                    'nomuval' => $emballage
                ]);
            }
        }
    }

    /**
     * @Route("uval_nb", name="uval_nb")
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
        }
        return $this->redirectToRoute('uval_index');
    }

    /**
     * Insertion et affichage des emballages
     * @Route("uval", name="uval_index")
     */
    public function emballage(SessionInterface $session, Service $service)
    {
        if (!empty($session->get('nb_row', []))) {
            $sessionLigne = $session->get('nb_row', []);
        } else {
            $sessionLigne = 1;
        }

        $sessionNb = $sessionLigne;
        $nb_row = array(1);

        if (!empty($sessionNb)) {
            for ($i = 0; $i < $sessionNb; $i++) {
                $nb_row[$i] = $i;
            }
        }

        // Gestion du POST
        if (isset($_POST['enregistrer'])) {
            for ($i = 0; $i < $sessionNb; $i++) {
                $data = [
                    'emballage' => $_POST['emballage' . $i],
                ];
                $service->new_uval($data);
            }
        }

        return $this->render('uval/index.html.twig', [
            'nb_rows' => $nb_row,
            'uvals' => $service->repo_uval->findAll(),
            'familles' => $service->repo_famille->findAll()
        ]);
    }

    /**
     * @Route("emballage_{id}", name="emballage_show", methods={"GET"})
     */
    public function show(Uval $emballage): Response
    {
        return $this->render('emballage/show.html.twig', [
            'emballage' => $emballage,
        ]);
    }

    /**
     * @Route("uval_{id}_delete", name="uval_delete", methods={"POST"})
     */
    public function delete(Request $request, Uval $emballage, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$emballage->getId(), $request->request->get('_token'))) {
            $entityManager->remove($emballage);
            $entityManager->flush();
        }

        return $this->redirectToRoute('uval_index', [], Response::HTTP_SEE_OTHER);
    }
}
