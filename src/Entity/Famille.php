<?php

namespace App\Entity;

use App\Repository\FamilleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FamilleRepository::class)
 */
class Famille
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
    private $nomfam;

    /**
     * @ORM\OneToMany(targetEntity=Produit::class, mappedBy="famille")
     */
    private $produits;

    /**
     * @ORM\OneToMany(targetEntity=Margeprix::class, mappedBy="famille")
     */
    private $margeprixes;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
        $this->margeprixes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nomfam;
    }

    public function setNom(string $nom): self
    {
        $this->nomfam = $nom;

        return $this;
    }

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
            $produit->setFamille($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): self
    {
        if ($this->produits->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getFamille() === $this) {
                $produit->setFamille(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Margeprix[]
     */
    public function getMargeprixes(): Collection
    {
        return $this->margeprixes;
    }

    public function addMargeprix(Margeprix $margeprix): self
    {
        if (!$this->margeprixes->contains($margeprix)) {
            $this->margeprixes[] = $margeprix;
            $margeprix->setFamille($this);
        }

        return $this;
    }

    public function removeMargeprix(Margeprix $margeprix): self
    {
        if ($this->margeprixes->removeElement($margeprix)) {
            // set the owning side to null (unless already changed)
            if ($margeprix->getFamille() === $this) {
                $margeprix->setFamille(null);
            }
        }

        return $this;
    }

    
}
