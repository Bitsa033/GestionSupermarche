<?php

namespace App\Entity;

use App\Repository\CapaciteMagasinRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CapaciteMagasinRepository::class)
 */
class CapaciteMagasin
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=Magasin::class, mappedBy="capaciteMagasin")
     */
    private $magasin;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbInitial;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbActuel;

    public function __construct()
    {
        $this->magasin = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Magasin[]
     */
    public function getMagasin(): Collection
    {
        return $this->magasin;
    }

    public function addMagasin(Magasin $magasin): self
    {
        if (!$this->magasin->contains($magasin)) {
            $this->magasin[] = $magasin;
            $magasin->setCapaciteMagasin($this);
        }

        return $this;
    }

    public function removeMagasin(Magasin $magasin): self
    {
        if ($this->magasin->removeElement($magasin)) {
            // set the owning side to null (unless already changed)
            if ($magasin->getCapaciteMagasin() === $this) {
                $magasin->setCapaciteMagasin(null);
            }
        }

        return $this;
    }

    public function getNbInitial(): ?int
    {
        return $this->nbInitial;
    }

    public function setNbInitial(int $nbInitial): self
    {
        $this->nbInitial = $nbInitial;

        return $this;
    }

    public function getNbActuel(): ?int
    {
        return $this->nbActuel;
    }

    public function setNbActuel(int $nbActuel): self
    {
        $this->nbActuel = $nbActuel;

        return $this;
    }
}
