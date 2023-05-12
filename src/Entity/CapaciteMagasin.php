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
     * @ORM\Column(type="integer")
     */
    private $capaciteActuel;

    /**
     * @ORM\ManyToOne(targetEntity=Magasin::class, inversedBy="capaciteMagasins", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $magasin;

    public function __construct()
    {
        
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCapaciteActuel(): ?int
    {
        return $this->capaciteActuel;
    }

    public function setCapaciteActuel(int $capaciteActuel): self
    {
        $this->capaciteActuel = $capaciteActuel;

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
}
