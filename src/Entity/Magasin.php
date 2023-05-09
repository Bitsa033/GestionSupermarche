<?php

namespace App\Entity;

use App\Repository\MagasinRepository;
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
     * @ORM\ManyToOne(targetEntity=CapaciteMagasin::class, inversedBy="magasin")
     * @ORM\JoinColumn(nullable=false)
     */
    private $capaciteMagasin;

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

    public function getCapaciteMagasin(): ?CapaciteMagasin
    {
        return $this->capaciteMagasin;
    }

    public function setCapaciteMagasin(?CapaciteMagasin $capaciteMagasin): self
    {
        $this->capaciteMagasin = $capaciteMagasin;

        return $this;
    }
}
