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
    private $codep;

    /**
     * @ORM\Column(type="bigint")
     */
    private $alerte; //alerte de securite

    /**
     * @ORM\ManyToOne(targetEntity=Famille::class, inversedBy="produits")
     */
    private $famille;

    /**
     * @ORM\OneToMany(targetEntity=Achat::class, mappedBy="produit")
     */
    private $achats;

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
        return $this->codep;
    }

    public function setCode(string $codep): self
    {
        $this->codep = $codep;

        return $this;
    }

    public function getAlerte(): ?string
    {
        return $this->alerte;
    }

    public function setAlerte(string $alerte): self
    {
        $this->alerte = $alerte;

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


}
