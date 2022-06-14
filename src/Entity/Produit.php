<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProduitRepository::class)
 */
class Produit
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
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ref;

    /**
     * @ORM\ManyToOne(targetEntity=Famille::class, inversedBy="produits")
     */
    private $famille;

    /**
     * @ORM\OneToMany(targetEntity=Stock::class, mappedBy="produit")
     */
    private $stocks;

    /**
     * @ORM\Column(type="bigint")
     */
    private $masse;

    /**
     * @ORM\ManyToOne(targetEntity=Uval::class, inversedBy="produits")
     * @ORM\JoinColumn(nullable=false)
     */
    private $uvalp;

    public function __construct()
    {
        $this->stocks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getRef(): ?string
    {
        return $this->ref;
    }

    public function setRef(string $ref): self
    {
        $this->ref = $ref;

        return $this;
    }

    public function getFamille(): ?Famille
    {
        return $this->famille;
    }

    public function setFamille(?Famille $famille): self
    {
        $this->famille = $famille;

        return $this;
    }

    /**
     * @return Collection|Stock[]
     */
    public function getStocks(): Collection
    {
        return $this->stocks;
    }

    public function addStock(Stock $stock): self
    {
        if (!$this->stocks->contains($stock)) {
            $this->stocks[] = $stock;
            $stock->setProduit($this);
        }

        return $this;
    }

    public function removeStock(Stock $stock): self
    {
        if ($this->stocks->removeElement($stock)) {
            // set the owning side to null (unless already changed)
            if ($stock->getProduit() === $this) {
                $stock->setProduit(null);
            }
        }

        return $this;
    }

    public function getMasse(): ?string
    {
        return $this->masse;
    }

    public function setMasse(string $masse): self
    {
        $this->masse = $masse;

        return $this;
    }

    public function getUvalp(): ?Uval
    {
        return $this->uvalp;
    }

    public function setUvalp(?Uval $uvalp): self
    {
        $this->uvalp = $uvalp;

        return $this;
    }


}
