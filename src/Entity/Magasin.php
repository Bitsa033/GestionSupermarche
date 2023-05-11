<?php

namespace App\Entity;

use App\Repository\MagasinRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MagasinRepository::class)
 */
class Magasin
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="integer")
     */
    private $capacite;

    /**
     * @ORM\OneToMany(targetEntity=Reception::class, mappedBy="magasin")
     */
    private $receptions;

    /**
     * @ORM\OneToMany(targetEntity=CapaciteMagasin::class, mappedBy="magasin")
     */
    private $capaciteMagasins;

    public function __construct()
    {
        $this->receptions = new ArrayCollection();
        $this->capaciteMagasins = new ArrayCollection();
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

    public function getCapacite(): ?int
    {
        return $this->capacite;
    }

    public function setCapacite(int $capacite): self
    {
        $this->capacite = $capacite;

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
            $reception->setMagasin($this);
        }

        return $this;
    }

    public function removeReception(Reception $reception): self
    {
        if ($this->receptions->removeElement($reception)) {
            // set the owning side to null (unless already changed)
            if ($reception->getMagasin() === $this) {
                $reception->setMagasin(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CapaciteMagasin[]
     */
    public function getCapaciteMagasins(): Collection
    {
        return $this->capaciteMagasins;
    }

    public function addCapaciteMagasin(CapaciteMagasin $capaciteMagasin): self
    {
        if (!$this->capaciteMagasins->contains($capaciteMagasin)) {
            $this->capaciteMagasins[] = $capaciteMagasin;
            $capaciteMagasin->setMagasin($this);
        }

        return $this;
    }

    public function removeCapaciteMagasin(CapaciteMagasin $capaciteMagasin): self
    {
        if ($this->capaciteMagasins->removeElement($capaciteMagasin)) {
            // set the owning side to null (unless already changed)
            if ($capaciteMagasin->getMagasin() === $this) {
                $capaciteMagasin->setMagasin(null);
            }
        }

        return $this;
    }
}
