<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;

class ChartController extends AbstractController
{
     /**
     * Page qui affiche le graphique
     * @Route("/dashboard", name="dashboard")
     */
    public function index(ProduitRepository $produitRepository): Response
    {
        $produits = $produitRepository->findAll();

        $labels = [];
        $quantites = [];
        $colors = [];

        foreach ($produits as $produit) {
            $labels[] = $produit->getNom();
            $quantites[] = (int) ($produit->getQteEnStock() ?? 0);
            $colors[] = 'rgba(54, 162, 235, 0.7)'; // bleu pour chaque barre
        }

        // JSON encode pour Twig
        return $this->render('chart/index.html.twig', [
            'labels' => json_encode($labels),
            'quantites' => json_encode($quantites),
            'colors' => json_encode($colors),
        ]);
    
    }

    /**
     * @Route("/charts/stocks", name="charts_stocks")
     */
    public function stocks(ProduitRepository $produitRepository): JsonResponse
    {
        // Récupération des produits avec leur quantité en stock
        $produits = $produitRepository->findAll();

        // Préparation des données pour Chart.js
        $labels = [];
        $quantites = [];
        $valeurs = [];

        foreach ($produits as $produit) {
            $labels[] = $produit->getNom();
            $quantites[] = $produit->getQteEnStock() ?? 0;
            $valeurs[] = ($produit->getQteEnStock() ?? 0) * $produit->getPrixAchat();
        }

        return $this->render('chart/index.html.twig', [
            'labels' => json_encode($labels),
            'quantites' => json_encode($quantites),
            'valeurs' => json_encode($valeurs),
        ]);
    }
}
