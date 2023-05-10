<?php

namespace App\Entity;

use App\Repository\AbonnementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AbonnementRepository::class)]
class Abonnement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['Abn'])]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['Abn'])]
    private ?int $idU = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message:'champ vide')]
    #[Groups(['Abn'])]
    private ?string $moyenTransportA = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups(['Abn'])]
    private ?\DateTimeInterface $dateA = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups(['Abn'])]
    private ?\DateTimeInterface $dateExpA = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    #[Assert\Choice(choices: [0, 1])]
    #[Groups(['Abn'])]
    private ?int $etudiantA = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['Abn'])]
    private ?int $redEtA = null;

    #[ORM\ManyToOne(inversedBy: 'abonnements')]
    #[Assert\NotBlank(message:'champ vide')]
    private ?Typeabn $plan = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdU(): ?int
    {
        return $this->idU;
    }

    public function setIdU(?int $idU): self
    {
        $this->idU = $idU;

        return $this;
    }

    public function getMoyenTransportA(): ?string
    {
        return $this->moyenTransportA;
    }

    public function setMoyenTransportA(?string $moyenTransportA): self
    {
        $this->moyenTransportA = $moyenTransportA;

        return $this;
    }

    public function getDateA(): ?\DateTimeInterface
    {
        return $this->dateA;
    }

    public function setDateA(\DateTimeInterface $dateA): self
    {
        $this->dateA = $dateA;
        //$this->dateA = new \DateTime();

        return $this;
    }

    public function getDateExpA(): ?\DateTimeInterface
    {
        return $this->dateExpA;
    }

    public function setDateExpA(?\DateTimeInterface $dateExpA): self
    {
        $this->dateExpA = $dateExpA;

        return $this;
    }

    public function getEtudiantA(): ?int
    {
        return $this->etudiantA;
    }

    // public function setEtudiantA(?int $etudiantA): self
    // {
    //     $this->etudiantA = $etudiantA;

    //     return $this;
    // }
    public function setEtudiantA(?int $etudiantA): self
    {
        $this->etudiantA = $etudiantA;
        if ($this->etudiantA !== null) {
            $this->setRedEtA(20);
        } else {
            $this->setRedEtA(null);
        }
        return $this;
    }
    public function getRedEtA(): ?int
    {
        return $this->redEtA;
    }

    // public function setRedEtA(?int $redEtA): self
    // {
    //     $this->redEtA = $redEtA;

    //     return $this;
    // }
    public function setRedEtA(?int $redEtA): self
    {
        if ($this->etudiantA == 1) {
            $this->redEtA = 20;
        } else {
            $this->redEtA = 0;
        }
    
        return $this;
    }
    public function getPlan(): ?Typeabn
    {
        return $this->plan;
    }

    public function setPlan(?Typeabn $plan): self
    {
        $this->plan = $plan;

        return $this;
    }
    
}
