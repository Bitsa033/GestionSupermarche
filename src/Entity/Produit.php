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
     * @ORM\Column(type="string", length=255)
     */
    private $code;

    /**
     * @ORM\ManyToOne(targetEntity=Famille::class, inversedBy="produits")
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
     * @ORM\ManyToOne(targetEntity=Uval::class, inversedBy="achats")
     * @ORM\JoinColumn(nullable=false)
     */
    private $unite_achat; //unite d'achat

    /**
     * @ORM\ManyToOne(targetEntity=Uval::class, inversedBy="produits")
     * @ORM\JoinColumn(nullable=false)
     */
    private $unite_vente;

    public function __construct()
    {
        $this->achats = new ArrayCollection();
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


}
