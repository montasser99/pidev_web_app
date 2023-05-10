<?php

namespace App\Entity;

use App\Repository\AdresseRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdresseRepository::class)]
class Adresse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20) ]
    private ?string $region= null;
         
    #[ORM\Column(length: 20) ]
    private ?string $cite= null;
       
    #[ORM\Column(length: 20) ]
    private ?string $rue= null;
    
    #[ORM\Column]
    private ?int $numposte = null;
    
       
        public function getId(): ?int
        {
            return $this->id;
        }
        public function getRegion(): ?string
        {
            return $this->region;
        }
    
        public function setRegion(string $region): self
        {
            $this->region = $region;
    
            return $this;
        }
    
        public function getCite(): ?string
        {
            return $this->cite;
        }
    
        public function setCite(string $cite): self
        {
            $this->cite = $cite;
    
            return $this;
        }
    
        public function getRue(): ?string
        {
            return $this->rue;
        }
    
        public function setRue(string $rue): self
        {
            $this->rue = $rue;
    
            return $this;
        }
    
        public function getNumposte(): ?int
        {
            return $this->numposte;
        }
    
        public function setNumposte(int $numposte): self
        {
            $this->numposte = $numposte;
    
            return $this;
        }
        public function __toString()
        {
            return $this->region . ' ' . $this->cite . ', ' . $this->rue .$this->numposte;
        }
    
    }
    

