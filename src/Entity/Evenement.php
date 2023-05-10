<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Offre;
use App\Repository\EvenementRepository;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


#[ORM\Entity(repositoryClass: EvenementRepository::class)]
class Evenement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]
    private ?int $idEve;

    #[ORM\Column]
    #[Assert\NotBlank(message:'veuillez remplir tous les champs obligatoires')]
    #[Assert\Length(min:5, minMessage:'Le titre doit comporter au moins {{ limit }} caractères')]
    private ?string $titreEve=null;

    #[ORM\Column]
    #[Assert\NotBlank(message:'veuillez remplir tous les champs obligatoires')]
    private ?string $descEve = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message:'veuillez remplir tous les champs obligatoires')]
    #[Assert\LessThan(propertyPath: "dateFinEve", message: "La date de début doit être inférieure à la date de fin.")]
    private ?\DateTimeInterface $dateDebEve;


    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message:'veuillez remplir tous les champs obligatoires')]
    private ?\DateTimeInterface $dateFinEve;

    #[ORM\ManyToOne(targetEntity: Offre::class)]
    #[ORM\JoinColumn(name: 'offre_id', referencedColumnName: 'id_offre_eve')]
    private ?Offre $offreId=null;


    #[ORM\Column(type: 'float')]
    #[Assert\NotBlank(message: 'Veuillez remplir tous les champs obligatoires.')]
    private ?float $prix = null;

    #[ORM\Column(length: 255)]
    private ?string $image;


    #[ORM\OneToMany(mappedBy: 'evenement', targetEntity: Participation::class)]
    private Collection $participations;


    public function getIdEve(): ?int
    {
        return $this->idEve;
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
    public function getIdoffre(): ?Offre
    {
        return $this->offreId;
    }
    public function setIdoffre(Offre $offreId): self
    {
        $this->offreId = $offreId;

        return $this;
    }

    public function getOffreId(): ?Offre
    {
        return $this->offreId;
    }

    public function setOffreId(?Offre $offreId): self
    {
        $this->offreId = $offreId;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }


    
     /**
     * @return Collection<int, Participation>
     */
    public function getParticipations(): Collection
    {
        return $this->participations;
    }

    public function addParticipation(Participation $participation): self
    {
        if (!$this->participations->contains($participation)) {
            $this->participations[] = $participation;
            $participation->setEvenement($this);
        }

        return $this;
    }

    public function removeParticipation(Participation $participation): self
    {
        if ($this->participations->removeElement($participation)) {
            // set the owning side to null (unless already changed)
            if ($participation->getEvenement() === $this) {
                $participation->setEvenement(null);
            }
        }

        return $this;
    }

}
