<?php

namespace App\Entity;
use App\Repository\CircuitRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: CircuitRepository::class)]


class Circuit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer" , name: "idcircuit")]
    private ?int $idcircuit = null;


     #[ORM\Column(length: 150 , name: "departC" , type: "string")]
     #[Assert\NotBlank (message:" Champs obligatoire")]
     private  ?string $departc=null;
   

     #[ORM\Column(length: 150 , name: "arriveeC" , type: "string")]
     #[Assert\NotBlank (message:" Champs obligatoire")]
         private  ?string $arriveec=null;

     #[ORM\Column(length: 150 , name: "nomC" , type: "string")]
     #[Assert\NotBlank (message:" Champs obligatoire")]
     private  ?string $nomc=null;

     public function getIdcircuit(): ?int
     {
         return $this->idcircuit;
     }

     public function getDepartc(): ?string
     {
         return $this->departc;
     }

     public function setDepartc(string $departc): self
     {
         $this->departc = $departc;

         return $this;
     }

     public function getArriveec(): ?string
     {
         return $this->arriveec;
     }

     public function setArriveec(string $arriveec): self
     {
         $this->arriveec = $arriveec;

         return $this;
     }

     public function getNomc(): ?string
     {
         return $this->nomc;
     }

     public function setNomc(string $nomc): self
     {
         $this->nomc = $nomc;

         return $this;
     }
    

}
