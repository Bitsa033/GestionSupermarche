<?php

namespace App\Entity;

use App\Repository\AchatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AchatRepository::class)
 */
class Achat
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Produit::class, inversedBy="achats")
     * @ORM\JoinColumn(nullable=false)
     */
    private $produit;

    /**
     * @ORM\Column(type="bigint")
     */
    private $qte; //qte d'achat

    /**
     * @ORM\Column(type="bigint")
     */
    private $prix_total; //prix d'achat total du produit

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateachat;

    /**
     * @ORM\OneToMany(targetEntity=Reception::class, mappedBy="commande")
     */
    private $receptions;

    public function __construct()
    {
        $this->receptions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getQte(): ?string
    {
        return $this->qte;
    }

    public function setQte(string $qte): self
    {
        $this->qte = $qte;

        return $this;
    }

    public function getPrixTotal(): ?string
    {
        return $this->prix_total;
    }
    
    public function setPrixATotal(string $prix_total): self
    {
        $this->prix_total = $prix_total;
        
        return $this;
    }

    public function getDateachat(): ?\DateTimeInterface
    {
        return $this->dateachat;
    }

    public function setDateachat(\DateTimeInterface $dateachat): self
    {
        $this->dateachat = $dateachat;

        return $this;
    }

    /**
     * @return Collection|Reception[]
     */
    public function getReceptions(): Collection
    {
        return $this->receptions;
    }

    public function addReception(Reception $reception): self
    {
        if (!$this->receptions->contains($reception)) {
            $this->receptions[] = $reception;
            $reception->setCommande($this);
        }

        return $this;
    }

    public function removeReception(Reception $reception): self
    {
        if ($this->receptions->removeElement($reception)) {
            // set the owning side to null (unless already changed)
            if ($reception->getCommande() === $this) {
                $reception->setCommande(null);
            }
        }

        return $this;
    }
}
