<?php

namespace App\Entity;

use App\Repository\UvalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UvalRepository::class)
 */
class Uval
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nomuval;

    /**
     * @ORM\OneToMany(targetEntity=Stock::class, mappedBy="uvalst")
     */
    private $stocks;

    /**
     * @ORM\OneToMany(targetEntity=Achat::class, mappedBy="unitea")
     */
    private $achats;

    /**
     * @ORM\OneToMany(targetEntity=Produit::class, mappedBy="unite_vente")
     */
    private $produits;

    public function __construct()
    {
        $this->stocks = new ArrayCollection();
        $this->achats = new ArrayCollection();
        $this->produits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomuval(): ?string
    {
        return $this->nomuval;
    }

    public function setNomuval(string $nomuval): self
    {
        $this->nomuval = $nomuval;

        return $this;
    }

    /**
     * @return Collection|Stock[]
     */
    public function getStocks(): Collection
    {
        return $this->stocks;
    }

    // public function addStock(Stock $stock): self
    // {
    //     if (!$this->stocks->contains($stock)) {
    //         $this->stocks[] = $stock;
    //         $stock->setUniteStockage($this);
    //     }

    //     return $this;
    // }

    // public function removeStock(Stock $stock): self
    // {
    //     if ($this->stocks->removeElement($stock)) {
    //         // set the owning side to null (unless already changed)
    //         if ($stock->getUniteStockage() === $this) {
    //             $stock->setUniteStockage(null);
    //         }
    //     }

    //     return $this;
    // }

    /**
     * @return Collection|Achat[]
     */
    public function getAchats(): Collection
    {
        return $this->achats;
    }

    // public function addAchat(Achat $achat): self
    // {
    //     if (!$this->achats->contains($achat)) {
    //         $this->achats[] = $achat;
    //         $achat->setUniteAchat($this);
    //     }

    //     return $this;
    // }

    // public function removeAchat(Achat $achat): self
    // {
    //     if ($this->achats->removeElement($achat)) {
    //         // set the owning side to null (unless already changed)
    //         if ($achat->getUniteAchat() === $this) {
    //             $achat->setUniteAchat(null);
    //         }
    //     }

    //     return $this;
    // }

    /**
     * @return Collection|Produit[]
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): self
    {
        if (!$this->produits->contains($produit)) {
            $this->produits[] = $produit;
            $produit->setUniteVente($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): self
    {
        if ($this->produits->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getUniteVente() === $this) {
                $produit->setUniteVente(null);
            }
        }

        return $this;
    }
}
