<?php

namespace App\Entity;

use App\Repository\OperationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OperationRepository::class)
 */
class Operation
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
    private $numeroOperation;

    /**
     * @ORM\Column(type="date")
     */
    private $dateOperation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $montant;

    /**
     * @ORM\ManyToOne(targetEntity=Compte::class, inversedBy="operations")
     */
    private $compte;

    /**
     * @ORM\ManyToOne(targetEntity=TypeOperation::class, inversedBy="operations")
     */
    private $type_Operation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumeroOperation(): ?string
    {
        return $this->numeroOperation;
    }

    public function setNumeroOperation(string $numeroOperation): self
    {
        $this->numeroOperation = $numeroOperation;

        return $this;
    }

    public function getDateOperation(): ?\DateTimeInterface
    {
        return $this->dateOperation;
    }

    public function setDateOperation(\DateTimeInterface $dateOperation): self
    {
        $this->dateOperation = $dateOperation;

        return $this;
    }

    public function getMontant(): ?string
    {
        return $this->montant;
    }

    public function setMontant(string $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getCompte(): ?Compte
    {
        return $this->compte;
    }

    public function setCompte(?Compte $compte): self
    {
        $this->compte = $compte;

        return $this;
    }

    public function getTypeOperation(): ?TypeOperation
    {
        return $this->type_Operation;
    }

    public function setTypeOperation(?TypeOperation $type_Operation): self
    {
        $this->type_Operation = $type_Operation;

        return $this;
    }
}
