<?php

namespace App\Entity;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PlanningRepository;
Use App\Entity\Moyenstransport;
Use App\Entity\Circuit;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PlanningRepository::class)]
class Planning
{
    #[ORM\Id]
    #[ORM\Column]
    #[Assert\NotBlank (message:" L'heure de depart ne peut pas être vide")]
    private ?string $dated= null;

    #[ORM\Column]
    #[Assert\NotBlank (message:" L'heure d'arrivée ne peut pas être vide")]
    private ?string $datea = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Type(type: 'integer')]
    private ?int $nbplace = null;

    #[ORM\Column(nullable: true)]
    #[Assert\NotBlank (message:"Le prix ne peut pas être vide")]
    #[Assert\GreaterThan(value: 550, message: "Le prix doit être supérieur à 550")]

    private ?int $prix = null;
  
    #[ORM\ManyToOne(targetEntity: Circuit::class)]
    #[ORM\JoinColumn(name: 'idcir', referencedColumnName: 'idcircuit' )]
    #[Assert\NotBlank (message:" Le circuit ne peut pas être vide")]
    private ?Circuit $idcir = null;
    
    #[ORM\ManyToOne(targetEntity: Moyenstransport::class)]
    #[ORM\JoinColumn(name: 'idmoy', referencedColumnName: 'idmoy')]
    #[Assert\NotBlank (message:" Le circuit ne peut pas être vide")]
 
    private ?Moyenstransport $idmoy = null;

    public function getDated(): string
    {
        return $this->dated;
    }

    public function getDatea(): string
    {
        return $this->datea;
    }

    public function setDatea(string $datea): self
    {
        $this->datea = $datea;

        return $this;
    }

    public function setDated(string $dated): self
    {
        $this->dated = $dated;

        return $this;
    }

    public function getNbplace(): ?int
    {
        return $this->nbplace;
    }

    public function setNbplace(int $nbplace): self
    {
        $this->nbplace = $nbplace;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getIdcir(): ?Circuit
    {
        return $this->idcir;
    }

    public function setIdcir(circuit $idcir): self
    {
        $this->idcir = $idcir;

        return $this;
    }

    public function getIdmoy(): ?Moyenstransport
    {
        return $this->idmoy;
    }

    public function setIdmoy(Moyenstransport $idmoy): self
    {
        $this->idmoy = $idmoy;

        return $this;
    }
    public function __toString()
    {
        return $this->getIdcir()->getNomc() ;
        
    }
    public function getNomc()
    {
        return $this->getIdcir()->getNomc();
    }

    public function getType()
    {
        return $this->getIdmoy()->getType();
    }

    
}