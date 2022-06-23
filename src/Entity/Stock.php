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
     * @ORM\ManyToOne(targetEntity=Achat::class, inversedBy="stocks")
     */
    private $achat;

    /**
     * @ORM\Column(type="bigint")
     */
    private $qs;//qte de stockage

    /**
     * @ORM\Column(type="bigint")
     */
    private $pvts; //prix de vente total du stock

    /**
     * @ORM\Column(type="bigint")
     */
    private $pvus; //prix de vente unitaire du stock

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
    private $us; //unite de stockage

    /**
     * @ORM\Column(type="bigint")
     */
    private $qgvs; //qte generale de valorisation du stock

    /**
     * @ORM\Column(type="string", length=5)
     */
    private $c; //comparateur

    /**
     * @ORM\Column(type="bigint")
     */
    private $qgu; //qte generale d'unites

    /**
     * @ORM\ManyToOne(targetEntity=Uval::class, inversedBy="stocks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $uvs; //unite de vente du stock

    /**
     * @ORM\Column(type="bigint")
     */
    private $qtu; //qte totale d'unites

    /**
     * @ORM\Column(type="bigint")
     */
    private $puvs;//prix de l'unite de vente du stock

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
        return $this->qs;
    }

    public function setQteStockage(string $qs): self
    {
        $this->qs = $qs;

        return $this;
    }

    public function getPrixVenteTotaleStock(): ?string
    {
        return $this->pvts;
    }

    public function setPrixVenteTotaleStock(string $pvts): self
    {
        $this->pvts = $pvts;

        return $this;
    }

    public function getPrixVenteUnitaireStock(): ?string
    {
        return $this->pvus;
    }

    public function setPrixVenteUnitaireStock(string $pvus): self
    {
        $this->pvus = $pvus;

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
        return $this->us;
    }

    public function setUniteStockage(?Uval $us): self
    {
        $this->us = $us;

        return $this;
    }

    public function getQteGenValStock(): ?string
    {
        return $this->qgvs;
    }

    public function setQteGenValStock(string $qgvs): self
    {
        $this->qgvs = $qgvs;

        return $this;
    }

    public function getC(): ?string
    {
        return $this->c;
    }

    public function setC(string $c): self
    {
        $this->c = $c;

        return $this;
    }

    public function getQteGenUnite(): ?string
    {
        return $this->qgu;
    }

    public function setQteGenUnite(string $qgu): self
    {
        $this->qgu = $qgu;

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

    public function getQteTotaleUnite(): ?string
    {
        return $this->qtu;
    }

    public function setQteTotaleUnite(string $qtu): self
    {
        $this->qtu = $qtu;

        return $this;
    }

    public function getPrixUniteVenteStock(): ?string
    {
        return $this->puvs;
    }

    public function setPrixUniteVenteStock(string $puvs): self
    {
        $this->puvs = $puvs;

        return $this;
    }
}
