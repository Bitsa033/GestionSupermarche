<?php

namespace App\Entity;

use App\Repository\StockRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\ManyToOne(targetEntity=Reception::class, inversedBy="stocks", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $reception; // produit

    /**
     * @ORM\Column(type="bigint")
     */
    private $qte_init;//qte initiale

    /**
     * @ORM\Column(type="bigint")
     */
    private $qte_tot;//qte totale

    /**
     * @ORM\Column(type="bigint")
     */
    private $prix_total; //prix total de stockage

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_stockage;

    /**
     * @ORM\OneToMany(targetEntity=SortieStock::class, mappedBy="stock")
     */
    private $sortieStocks;

    public function __construct()
    {
        $this->sortieStocks = new ArrayCollection();
    } 

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQteInit(): ?string
    {
        return $this->qte_init;
    }

    public function setQteInit(string $qte_init): self
    {
        $this->qte_init = $qte_init;

        return $this;
    }

    public function getQteTot(): ?string
    {
        return $this->qte_tot;
    }

    public function setQteTot(string $qte_tot): self
    {
        $this->qte_tot = $qte_tot;

        return $this;
    }

    public function getPrixTotal(): ?string
    {
        return $this->prix_total;
    }

    public function setPrixTotal(string $prix_total): self
    {
        $this->prix_total = $prix_total;

        return $this;
    }
    

    public function getReception(): ?Reception
    {
        return $this->reception;
    }

    public function setReception(?Reception $reception): self
    {
        $this->reception = $reception;

        return $this;
    }

    public function getDateStockage(): ?\DateTimeInterface
    {
        return $this->date_stockage;
    }

    public function setDateStockage(\DateTimeInterface $date_stockage): self
    {
        $this->date_stockage = $date_stockage;

        return $this;
    }

    /**
     * @return Collection|SortieStock[]
     */
    public function getSortieStocks(): Collection
    {
        return $this->sortieStocks;
    }

    public function addSortieStock(SortieStock $sortieStock): self
    {
        if (!$this->sortieStocks->contains($sortieStock)) {
            $this->sortieStocks[] = $sortieStock;
            $sortieStock->setStock($this);
        }

        return $this;
    }

    public function removeSortieStock(SortieStock $sortieStock): self
    {
        if ($this->sortieStocks->removeElement($sortieStock)) {
            // set the owning side to null (unless already changed)
            if ($sortieStock->getStock() === $this) {
                $sortieStock->setStock(null);
            }
        }

        return $this;
    }

}
