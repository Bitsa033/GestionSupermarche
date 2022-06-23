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
    private $qa; //qte d'achat

    /**
     * @ORM\Column(type="bigint")
     */
    private $patp; //prix d'achat total du produit

    /**
     * @ORM\Column(type="bigint")
     */
    private $paup; //prix d'achat unitaire du produit

    /**
     * @ORM\ManyToOne(targetEntity=Uval::class, inversedBy="achats")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ua; //unite d'achat

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateachat;

    /**
     * @ORM\OneToMany(targetEntity=Stock::class, mappedBy="achat")
     */
    private $stocks;

    public function __construct()
    {
        $this->stocks = new ArrayCollection();
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

    public function getQteAchat(): ?string
    {
        return $this->qa;
    }

    public function setQteAchat(string $qa): self
    {
        $this->qa = $qa;

        return $this;
    }

    public function getPrixAchatTotal(): ?string
    {
        return $this->patp;
    }
    
    public function setPrixAchatTotal(string $patp): self
    {
        $this->patp = $patp;
        
        return $this;
    }

    public function getPrixAchatUnitaire(): ?string
    {
        return $this->paup;
    }

    public function setPrixAchatUnitaire(string $paup): self
    {
        $this->paup = $paup;

        return $this;
    }

    public function getUniteAchat(): ?Uval
    {
        return $this->ua;
    }

    public function setUniteAchat(?Uval $ua): self
    {
        $this->ua = $ua;

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
            $stock->setAchat($this);
        }

        return $this;
    }

    public function removeStock(Stock $stock): self
    {
        if ($this->stocks->removeElement($stock)) {
            // set the owning side to null (unless already changed)
            if ($stock->getAchat() === $this) {
                $stock->setAchat(null);
            }
        }

        return $this;
    }
}
