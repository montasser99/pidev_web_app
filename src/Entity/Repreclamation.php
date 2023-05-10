<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use App\Repository\RepreclamationRepository;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Nullable;

#[ORM\Entity(repositoryClass: RepreclamationRepository::class)]
class Repreclamation
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(name: 'idrep', type: 'integer')]
    private $idrep;

    #[ORM\Column(type: 'datetime', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeInterface $daterep;

    #[ORM\Column(name: 'NomAg', type: 'string', length: 50)]
    private $nomag;

    #[ORM\Column(name: 'reponse', type: 'text', length: 16777215 , )]
    private $reponse;

    
    #[ORM\Column(name:'idr')]
    #[ORM\ManyToOne( targetEntity: Reclamation::class ,inversedBy:'RepReclamation',cascade: ['persist', 'remove'])]
    private $idr ;

    public function getIdrep()
    {
        return $this->idrep;
    }

    public function setIdrep($idrep)
    {
        $this->idrep = $idrep;
    }

    public function getDaterep()
    {
        return $this->daterep;
    }

    public function setDaterep($daterep)
    {
        $this->daterep = $daterep;  
        // new \DateTime();
    }

    public function getNomag()
    {
        return $this->nomag;
    }

    public function setNomag($nomag)
    {
        $this->nomag = $nomag;
    }

    public function getReponse()
    {
        return $this->reponse;
    }

    public function setReponse($reponse)
    {
        $this->reponse = $reponse;
    }

    public function getIdr() :?int
    {
        return $this->idr;
    }

    public function setIdr($reclamation): self
    {
        $this->idr = $reclamation;
        return $this;
    }
    
}