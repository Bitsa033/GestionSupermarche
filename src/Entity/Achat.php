<?php

namespace App\Entity;

use App\Repository\AchatRepository;
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
    private $qtep;

    /**
     * @ORM\Column(type="bigint")
     */
    private $pau;

    /**
     * @ORM\Column(type="bigint")
     */
    private $pat;

    /**
     * @ORM\ManyToOne(targetEntity=Uval::class, inversedBy="achats")
     * @ORM\JoinColumn(nullable=false)
     */
    private $unitea;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateachat;

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

    public function getQtep(): ?string
    {
        return $this->qtep;
    }

    public function setQtep(string $qtep): self
    {
        $this->qtep = $qtep;

        return $this;
    }

    public function getPau(): ?string
    {
        return $this->pau;
    }

    public function setPau(string $pau): self
    {
        $this->pau = $pau;

        return $this;
    }

    public function getPat(): ?string
    {
        return $this->pat;
    }

    public function setPat(string $pat): self
    {
        $this->pat = $pat;

        return $this;
    }

    public function getUnitea(): ?Uval
    {
        return $this->unitea;
    }

    public function setUnitea(?Uval $unitea): self
    {
        $this->unitea = $unitea;

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
}
