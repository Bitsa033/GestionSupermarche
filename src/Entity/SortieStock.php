<?php

namespace App\Entity;

use App\Repository\SortieStockRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SortieStockRepository::class)
 */
class SortieStock
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Stock::class, inversedBy="sortieStocks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $stock;

    /**
     * @ORM\Column(type="bigint")
     */
    private $qteSortie;

    /**
     * @ORM\Column(type="float")
     */
    private $valeur;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateSortie;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStock(): ?Stock
    {
        return $this->stock;
    }

    public function setStock(?Stock $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getQteSortie(): ?string
    {
        return $this->qteSortie;
    }

    public function setQteSortie(string $qteSortie): self
    {
        $this->qteSortie = $qteSortie;

        return $this;
    }

    public function getValeur(): ?float
    {
        return $this->valeur;
    }

    public function setValeur(float $valeur): self
    {
        $this->valeur = $valeur;

        return $this;
    }

    public function getDateSortie(): ?\DateTimeInterface
    {
        return $this->dateSortie;
    }

    public function setDateSortie(\DateTimeInterface $dateSortie): self
    {
        $this->dateSortie = $dateSortie;

        return $this;
    }
}
