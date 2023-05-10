<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AlerteRepository;


#[ORM\Entity(repositoryClass: AlerteRepository::class)]

class Alerte
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idAlerteEve=null;

    #[ORM\Column]
    #[Assert\NotBlank(message:'veuillez remplir tous les champs obligatoires')]
    private ?string $typeAlerteEve=null;

    #[ORM\Column]
    #[Assert\NotBlank(message:'veuillez remplir tous les champs obligatoires')]
    private ?string $titreEve=null;

    #[ORM\Column]
    #[Assert\NotBlank(message:'veuillez remplir tous les champs obligatoires')]
    #[Assert\Length(min:5, minMessage:'Le titre doit comporter au moins {{ limit }} caractères')]
    private ?string $descEve=null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message:'veuillez remplir tous les champs obligatoires')]
    #[Assert\LessThan(propertyPath: "dateFinEve", message: "La date de début doit être inférieure à la date de fin.")]
    private ?\DateTimeInterface $dateDebEve = null;
    
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message:'veuillez remplir tous les champs obligatoires')]

    private ?\DateTimeInterface $dateFinEve = null;


    public function getIdAlerteEve(): ?int
    {
        return $this->idAlerteEve;
    }

    public function getTypeAlerteEve(): ?string
    {
        return $this->typeAlerteEve;
    }

    public function setTypeAlerteEve(string $typeAlerteEve): self
    {
        $this->typeAlerteEve = $typeAlerteEve;

        return $this;
    }

    public function getTitreEve(): ?string
    {
        return $this->titreEve;
    }

    public function setTitreEve(string $titreEve): self
    {
        $this->titreEve = $titreEve;

        return $this;
    }

    public function getDescEve(): ?string
    {
        return $this->descEve;
    }

    public function setDescEve(string $descEve): self
    {
        $this->descEve = $descEve;

        return $this;
    }

    public function getDateDebEve(): ?\DateTimeInterface
    {
        return $this->dateDebEve;
    }

    public function setDateDebEve(\DateTimeInterface $dateDebEve): self
    {
        $this->dateDebEve = $dateDebEve;

        return $this;
    }

    public function getDateFinEve(): ?\DateTimeInterface
    {
        return $this->dateFinEve;
    }

    public function setDateFinEve(\DateTimeInterface $dateFinEve): self
    {
        $this->dateFinEve = $dateFinEve;

        return $this;
    }


}
