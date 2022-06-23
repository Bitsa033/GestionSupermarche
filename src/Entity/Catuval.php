<?php

namespace App\Entity;

use App\Repository\CatuvalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CatuvalRepository::class)
 */
class Catuval
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
    private $nomcatuval;

    /**
     * @ORM\OneToMany(targetEntity=Uval::class, mappedBy="catuval")
     */
    private $uvals;

    public function __construct()
    {
        $this->uvals = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomcatuval(): ?string
    {
        return $this->nomcatuval;
    }

    public function setNomcatuval(string $nomcatval): self
    {
        $this->nomcatuval = $nomcatval;

        return $this;
    }

    /**
     * @return Collection|Uval[]
     */
    public function getUvals(): Collection
    {
        return $this->uvals;
    }

    public function addUval(Uval $uval): self
    {
        if (!$this->uvals->contains($uval)) {
            $this->uvals[] = $uval;
            $uval->setCatuval($this);
        }

        return $this;
    }

    public function removeUval(Uval $uval): self
    {
        if ($this->uvals->removeElement($uval)) {
            // set the owning side to null (unless already changed)
            if ($uval->getCatuval() === $this) {
                $uval->setCatuval(null);
            }
        }

        return $this;
    }
}
