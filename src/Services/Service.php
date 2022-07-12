<?php

namespace App\Services;
use App\Repository\UvalRepository;
use App\Repository\AchatRepository;
use App\Entity\Stock;
use Doctrine\Persistence\ManagerRegistry;

class Service{
    
    function StockInsertion($data,UvalRepository $uvalRepository,AchatRepository $achatRepository, ManagerRegistry $end)
    {
        foreach ($data as $key => $value) {
            $k[] = $key;
            $v[] = $value;
        }
        $k = implode(",", $k);
        $v = implode(",", $v);
        
        $achat=$achatRepository->find($data['achat']);
        $uniteVente=$uvalRepository->find($data['uniteVente']);
        
        $stock = new Stock();
        $stock->setAchat($achat);
        $stock->setQteStockage($data['quantiteStockage']);
        $stock->setPrixVenteUnitaireStock($data['prixVenteUnitaireStock']);
        $stock->setPrixVenteTotaleStock($data['prixVenteTotaleStock']);
        $stock->setProfitUnitaireStock($data['profitUnitaireStock']);
        $stock->setProfitTotalStock($data['profitTotalStock']);
        $stock->setUniteStockage($uniteVente);
        // $stock->setRef(strtoupper($data['ref']));
        $manager = $end->getManager();
        $manager->persist($stock);
        $manager->flush();
    }

    function StockUpdate($data,Stock $stock, ManagerRegistry $end)
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
    
}