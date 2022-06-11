<?php

namespace App\Entity;

use App\Repository\StockRepository;
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
     * @ORM\ManyToOne(targetEntity=Produit::class, inversedBy="stocks")
     */
    private $produit;

    /**
     * @ORM\Column(type="bigint")
     */
    private $qt;

    /**
     * @ORM\Column(type="bigint")
     */
    private $qs;

    /**
     * @ORM\Column(type="date")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="bigint")
     */
    private $pat;

    /**
     * @ORM\Column(type="bigint")
     */
    private $pau;

    /**
     * @ORM\Column(type="bigint")
     */
    private $pvt;

    /**
     * @ORM\Column(type="bigint")
     */
    private $pvu;

    /**
     * @ORM\Column(type="bigint")
     */
    private $bvt;

    /**
     * @ORM\Column(type="bigint")
     */
    private $bvu;

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

    public function getQt(): ?string
    {
        return $this->qt;
    }

    public function setQt(string $qt): self
    {
        $this->qt = $qt;

        return $this;
    }

    public function getQs(): ?string
    {
        return $this->qs;
    }

    public function setQs(string $qs): self
    {
        $this->qs = $qs;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

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

    public function getPau(): ?string
    {
        return $this->pau;
    }

    public function setPau(string $pau): self
    {
        $this->pau = $pau;

        return $this;
    }

    public function getPvt(): ?string
    {
        return $this->pvt;
    }

    public function setPvt(string $pvt): self
    {
        $this->pvt = $pvt;

        return $this;
    }

    public function getPvu(): ?string
    {
        return $this->pvu;
    }

    public function setPvu(string $pvu): self
    {
        $this->pvu = $pvu;

        return $this;
    }

    public function getBvt(): ?string
    {
        return $this->bvt;
    }

    public function setBvt(string $bvt): self
    {
        $this->bvt = $bvt;

        return $this;
    }

    public function getBvu(): ?string
    {
        return $this->bvu;
    }

    public function setBvu(string $bvu): self
    {
        $this->bvu = $bvu;

        return $this;
    }
}
