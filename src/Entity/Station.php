<?php

namespace App\Entity;
use App\Repository\StationRepository;
use App\Entity\Circuit;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: StationRepository::class)]

class Station
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]

   private  ?int $idstation=null;

   #[ORM\Column(length: 150,name:'nomS')]
   #[Assert\NotBlank (message:" Champs obligatoire")]
   private  ?string $noms=null;

   #[ORM\Column(length: 150)]
   #[Assert\NotBlank (message:" Champs obligatoire")]
   private  ?string $adresse=null;

   
    
    #[ORM\ManyToOne(targetEntity: Circuit::class)]
    #[ORM\JoinColumn(name: 'idcircuit', referencedColumnName: 'idcircuit')]
    #[Assert\NotBlank (message:" Champs obligatoire")]
    private Circuit $idcircuit;

    public function getIdstation(): ?int
    {
        return $this->idstation;
    }

    public function getNomS(): ?string
    {
        return $this->noms;
    }

    public function setNomS(string $noms): self
    {
        $this->noms = $noms;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getIdcircuit()
    {
        return $this->idcircuit;
    }

    public function setIdcircuit(Circuit $idcircuit)
    {
        $this->idcircuit = $idcircuit;

        return $this;
    }
   

}
