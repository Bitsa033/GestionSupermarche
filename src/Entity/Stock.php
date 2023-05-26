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
     * @ORM\ManyToOne(targetEntity=Produit::class, inversedBy="stocks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $produit;


    public function __construct()
    {
        
    } 

    public function getId(): ?int
    {
        return $this->id;
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

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): self
    {
        $this->produit = $produit;

        return $this;
    }

}
