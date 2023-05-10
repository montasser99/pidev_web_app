<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;


use App\Repository\OffreRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: OffreRepository::class)]
class Offre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idOffreEve=null ;
    public function __toString()
    {
        return $this->getIdOffreEve();
    }
    
   
    #[ORM\ManyToOne(targetEntity:"App\Entity\Evenement")]
    #[ORM\JoinColumn(name:"idu", referencedColumnName:"id_eve")]
    private ?Evenement $idu=null;

    #[ORM\Column]
    #[Assert\NotBlank(message:'veuillez remplir tous les champs obligatoires')]
    #[Assert\Positive(message:'La durée doit être positif.')]
    private ?int $dureeOffre=null;

    #[ORM\Column]
    #[Assert\NotBlank(message:'veuillez remplir tous les champs obligatoires')]
    #[Assert\Positive(message:'Le code promo doit être positif.')]
    private ?float $budgetOffre=null;

    #[ORM\Column(nullable:true)]
    private ?string $imgSrcOffre=null;

    #[ORM\Column]
    #[Assert\NotBlank(message:'veuillez remplir tous les champs obligatoires')]
    private ?string $statutOffre=null;

    #[ORM\Column(nullable:true)] 
    private ?string $titreEve=null;

    #[ORM\Column(nullable:true)]
    private ?string $descEve=null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message:'veuillez remplir tous les champs obligatoires')]
    #[Assert\LessThan(propertyPath: "dateFinEve", message: "La date de début doit être inférieure à la date de fin.")]
    private ?\DateTimeInterface $dateDebEve = null;
    
    

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message:'veuillez remplir tous les champs obligatoires')]

    private ?\DateTimeInterface $dateFinEve = null;

    public function getIdOffreEve(): ?int
    {
        return $this->idOffreEve;
    }

    public function getIdu(): ?Evenement
    {
        return $this->idu;
    }

    public function setIdu(Evenement $idu): self
    {
        $this->idu = $idu;

        return $this;
    }

    public function getDureeOffre(): ?int
    {
        return $this->dureeOffre;
    }

    public function setDureeOffre(int $dureeOffre): self
    {
        $this->dureeOffre = $dureeOffre;

        return $this;
    }

    public function getBudgetOffre(): ?float
    {
        return $this->budgetOffre;
    }

    public function setBudgetOffre(float $budgetOffre): self
    {
        $this->budgetOffre = $budgetOffre;

        return $this;
    }

    public function getImgSrcOffre(): ?string
    {
        return $this->imgSrcOffre;
    }

    public function setImgSrcOffre(string $imgSrcOffre): self
    {
        $this->imgSrcOffre = $imgSrcOffre;

        return $this;
    }

    public function getStatutOffre(): ?string
    {
        return $this->statutOffre;
    }

    public function setStatutOffre(string $statutOffre): self
    {
        $this->statutOffre = $statutOffre;

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

    public function setDateDebEve(?\DateTimeInterface $dateDebEve): self
    {
        $this->dateDebEve = $dateDebEve;

        return $this;
    }

    public function getDateFinEve(): ?\DateTimeInterface
    {
        return $this->dateFinEve;
    }

    public function setDateFinEve(?\DateTimeInterface $dateFinEve): self
    {
        $this->dateFinEve = $dateFinEve;

        return $this;
    }


}
