<?php

namespace App\Controller;

use App\Entity\Magasin;
use App\Entity\Famille;
use App\Service\Service;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Connection;

class MagasinController extends AbstractController
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
        $this->initialiserMagasins();
    }

    private function initialiserMagasins(): void
    {
        $count = $this->connection->fetchOne('SELECT COUNT(*) FROM magasin');

        if ($count == 0) {
            $magasins = [
                'Magasin Central',
                'Magasin Annexe 1',
                'Magasin Annexe 2',
                'Magasin Entrepôt'
            ];

            foreach ($magasins as $magasin) {
                $this->connection->insert('magasin', [
                    'nom' => $magasin,
                    'capacite'=>5000
                ]);
            }
        }
    }

    /**
     * @Route("magasin_nb", name="magasin_nb")
     */
    public function nb(SessionInterface $session, Request $request)
    {
        if (!empty($request->request->get('nb_row'))) {
            $nb_of_row = $request->request->get('nb_row');
            $session->set('nb_row', $nb_of_row);
        }
        return $this->redirectToRoute('magasin_index');
    }

    /**
     * @Route("magasins", name="magasin_index")
     */
    public function magasin(SessionInterface $session, Service $service)
    {
        $sessionNb = $session->get('nb_row', 1);
        $nb_row = range(0, $sessionNb - 1);

        if (isset($_POST['enregistrer'])) {
            for ($i = 0; $i < $sessionNb; $i++) {
                $data = [
                    'magasin' => $_POST['magasin' . $i],
                    'capacite' => $_POST['capacite' . $i]
                ];
                $service->new_magasin($data);
            }
        }

        return $this->render('magasin/index.html.twig', [
            'nb_rows' => $nb_row,
            'magasins' => $service->repo_magasin->findAll(),
            'familles' => $service->repo_famille->findAll()
        ]);
    }

    

    /**
     * @Route("magasin_{id}_delete", name="magasin_delete", methods={"POST"})
     */
    public function delete(Request $request, Magasin $magasin, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$magasin->getId(), $request->request->get('_token'))) {
            $entityManager->remove($magasin);
            $entityManager->flush();
        }

        return $this->redirectToRoute('magasin_index', [], Response::HTTP_SEE_OTHER);
    }
}
