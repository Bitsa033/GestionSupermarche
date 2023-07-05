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
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255,unique=true)
     */
    private $code;

    /**
     * @ORM\ManyToOne(targetEntity=Famille::class, inversedBy="produits")
     * @ORM\JoinColumn(nullable=false)
     */
    private $famille;

    /**
     * @ORM\OneToMany(targetEntity=Achat::class, mappedBy="produit")
     */
    private $achats;

    /**
     * @ORM\Column(type="float")
     */
    private $prix_achat;

    /**
     * @ORM\Column(type="float")
     */
    private $prix_vente;

    /**
     * @ORM\ManyToOne(targetEntity=Uval::class, inversedBy="achats", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $unite_achat; //unite d'achat

    /**
     * @ORM\ManyToOne(targetEntity=Uval::class, inversedBy="produits", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $unite_vente;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $qte_en_stock;

    /**
     * @ORM\Column(type="string", length=5)
     */
    private $statut;

    /**
     * @ORM\OneToMany(targetEntity=SortieStock::class, mappedBy="produit")
     */
    private $sortieStocks;

    /**
     * @ORM\OneToMany(targetEntity=Stock::class, mappedBy="produit")
     */
    private $stocks;

    public function __construct()
    {
        $this->achats = new ArrayCollection();
        $this->sortieStocks = new ArrayCollection();
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

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getPrixAchat(): ?float
    {
        return $this->prix_achat;
    }

    public function setPrixAchat(float $prix_achat): self
    {
        $this->prix_achat = $prix_achat;

        return $this;
    }

    public function getPrixVente(): ?float
    {
        return $this->prix_vente;
    }

    public function setPrixVente(float $prix_vente): self
    {
        $this->prix_vente = $prix_vente;

        return $this;
    }

    public function getUniteAchat(): ?Uval
    {
        return $this->unite_achat;
    }

    public function setUniteAchat(?Uval $unite_achat): self
    {
        $this->unite_achat = $unite_achat;

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
     * @return Collection|Achat[]
     */
    public function getAchats(): Collection
    {
        return $this->achats;
    }

    public function addAchat(Achat $achat): self
    {
        if (!$this->achats->contains($achat)) {
            $this->achats[] = $achat;
            $achat->setProduit($this);
        }

        return $this;
    }

    public function removeAchat(Achat $achat): self
    {
        if ($this->achats->removeElement($achat)) {
            // set the owning side to null (unless already changed)
            if ($achat->getProduit() === $this) {
                $achat->setProduit(null);
            }
        }

        return $this;
    }

    public function getUniteVente(): ?Uval
    {
        return $this->unite_vente;
    }

    public function setUniteVente(?Uval $unite_vente): self
    {
        $this->unite_vente = $unite_vente;

        return $this;
    }

    public function getQteEnStock(): ?string
    {
        return $this->qte_en_stock;
    }

    public function setQteEnStock(?string $qte_en_stock): self
    {
        $this->qte_en_stock = $qte_en_stock;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

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
            $sortieStock->setProduit($this);
        }

        return $this;
    }

    public function removeSortieStock(SortieStock $sortieStock): self
    {
        if ($this->sortieStocks->removeElement($sortieStock)) {
            // set the owning side to null (unless already changed)
            if ($sortieStock->getProduit() === $this) {
                $sortieStock->setProduit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Stock>
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


}
