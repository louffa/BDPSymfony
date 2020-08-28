<?php

namespace App\Entity;

use App\Repository\RegionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RegionRepository::class)
 */
class Region
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity=Agence::class, mappedBy="region")
     */
    private $agences;

    public function __construct()
    {
        $this->agences = new ArrayCollection();
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
    public function __toString()
    {
        return $this->nom;
    }

    /**
     * @return Collection|Agence[]
     */
    public function getAgences(): Collection
    {
        return $this->agences;
    }

    public function addAgence(Agence $agence): self
    {
        if (!$this->agences->contains($agence)) {
            $this->agences[] = $agence;
            $agence->setRegion($this);
        }

        return $this;
    }

    public function removeAgence(Agence $agence): self
    {
        if ($this->agences->contains($agence)) {
            $this->agences->removeElement($agence);
            // set the owning side to null (unless already changed)
            if ($agence->getRegion() === $this) {
                $agence->setRegion(null);
            }
        }

        return $this;
    }
}
