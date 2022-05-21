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
    private $qd;

    /**
     * @ORM\Column(type="bigint")
     */
    private $qs;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

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

    public function getQd(): ?string
    {
        return $this->qd;
    }

    public function setQd(string $qd): self
    {
        $this->qd = $qd;

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
}
