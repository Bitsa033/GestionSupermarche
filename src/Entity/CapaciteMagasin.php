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
    private $nbInitial;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbActuel;

    /**
     * @ORM\ManyToOne(targetEntity=Magasin::class, inversedBy="capaciteMagasins")
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
