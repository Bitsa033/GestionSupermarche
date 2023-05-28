<?php

namespace App\Entity;

use App\Repository\ReceptionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReceptionRepository::class)
 */
class Reception
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Achat::class, inversedBy="receptions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $commande;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_reception;

    /**
     * @ORM\Column(type="float")
     */
    private $prix_total;

    /**
     * @ORM\ManyToOne(targetEntity=Magasin::class, inversedBy="receptions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $magasin;

    /**
     * @ORM\Column(type="bigint")
     */
    private $qte_unit_val;

    /**
     * @ORM\Column(type="bigint")
     */
    private $qte_tot_val;

    /**
     * @ORM\Column(type="bigint")
     */
    private $prix_tot_val;

    /**
     * @ORM\Column(type="bigint")
     */
    private $qte_rec;

    public function __construct()
    {
    
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommande(): ?Achat
    {
        return $this->commande;
    }

    public function setCommande(?Achat $commande): self
    {
        $this->commande = $commande;

        return $this;
    }

    public function getDateReception(): ?\DateTimeInterface
    {
        return $this->date_reception;
    }

    public function setDateReception(\DateTimeInterface $date_reception): self
    {
        $this->date_reception = $date_reception;

        return $this;
    }

    public function getPrixTotal(): ?float
    {
        return $this->prix_total;
    }

    public function setPrixTotal(float $prix_total): self
    {
        $this->prix_total = $prix_total;

        return $this;
    }

    public function getMagasin(): ?Magasin
    {
        return $this->magasin;
    }

    public function setMagasin(?Magasin $magasin): self
    {
        $this->magasin = $magasin;

        return $this;
    }

    public function getQteUnitVal(): ?string
    {
        return $this->qte_unit_val;
    }

    public function setQteUnitVal(string $qte_unit_val): self
    {
        $this->qte_unit_val = $qte_unit_val;

        return $this;
    }

    public function getQteTotVal(): ?string
    {
        return $this->qte_tot_val;
    }

    public function setQteTotVal(string $qte_tot_val): self
    {
        $this->qte_tot_val = $qte_tot_val;

        return $this;
    }

    public function getPrixTotVal(): ?string
    {
        return $this->prix_tot_val;
    }

    public function setPrixTotVal(string $prix_tot_val): self
    {
        $this->prix_tot_val = $prix_tot_val;

        return $this;
    }

    public function getQteRec(): ?string
    {
        return $this->qte_rec;
    }

    public function setQteRec(string $qte_rec): self
    {
        $this->qte_rec = $qte_rec;

        return $this;
    }
}
