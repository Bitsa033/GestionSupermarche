<?php

namespace App\Entity;

use App\Repository\StockRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StockRepository::class)
 */
class Stock
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Achat::class, inversedBy="stock")
     */
    private $achat;

    /**
     * @ORM\Column(type="bigint")
     */
    private $qts;//qte totale de stockage

    /**
     * @ORM\Column(type="bigint")
     */
    private $prixvts; //prix de vente total du stock

    /**
     * @ORM\Column(type="bigint")
     */
    private $prixvus; //prix de vente unitaire du stock

    /**
     * @ORM\Column(type="bigint")
     */
    private $pts; //profit total du stock

    /**
     * @ORM\Column(type="bigint")
     */
    private $pus; //profit unitaire du stock

    /**
     * @ORM\ManyToOne(targetEntity=Uval::class, inversedBy="stocks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $unitest; //unite de stockage

    /**
     * @ORM\ManyToOne(targetEntity=Uval::class, inversedBy="stocks")
     * @ORM\JoinColumn(nullable=true)
     */
    private $uvs; //unite de vente du stock


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAchat(): ?Achat
    {
        return $this->achat;
    }

    public function setAchat(?Achat $achat): self
    {
        $this->achat = $achat;

        return $this;
    }

    public function getQteStockage(): ?string
    {
        return $this->qts;
    }

    public function setQteStockage(string $qts): self
    {
        $this->qts = $qts;

        return $this;
    }

    public function getPrixVenteTotaleStock(): ?string
    {
        return $this->prixvts;
    }

    public function setPrixVenteTotaleStock(string $prixvts): self
    {
        $this->prixvts = $prixvts;

        return $this;
    }

    public function getPrixVenteUnitaireStock(): ?string
    {
        return $this->prixvus;
    }

    public function setPrixVenteUnitaireStock(string $prixvus): self
    {
        $this->prixvus = $prixvus;

        return $this;
    }

    public function getProfitTotalStock(): ?string
    {
        return $this->pts;
    }

    public function setProfitTotalStock(string $pts): self
    {
        $this->pts = $pts;

        return $this;
    }

    public function getProfitUnitaireStock(): ?string
    {
        return $this->pus;
    }

    public function setProfitUnitaireStock(string $pus): self
    {
        $this->pus = $pus;

        return $this;
    }

    public function getUniteStockage(): ?Uval
    {
        return $this->unitest;
    }

    public function setUniteStockage(?Uval $unitest): self
    {
        $this->unitest = $unitest;

        return $this;
    }

    public function getUniteVenteStock(): ?Uval
    {
        return $this->uvs;
    }

    public function setUniteVenteStock(?Uval $uvs): self
    {
        $this->uvs = $uvs;

        return $this;
    }

}
