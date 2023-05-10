<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\MoyenstransportRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MoyenstransportRepository::class)]
class Moyenstransport
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("moyens")]
    private ?int $idmoy= null;
 
    #[ORM\Column]
    #[Groups("moyens")]

    #[Assert\NotBlank (message:" Le type ne peut pas être vide")]
    private ?string $type=null;

    #[ORM\Column]
    #[Groups("moyens")]
    #[Assert\NotBlank (message:" Le matricule ne peut pas être vide")]
    private ?string $matricule =null;

    #[ORM\Column]
    #[Groups("moyens")]
    #[Assert\NotBlank (message:" Le capacite ne peut pas être vide")]
    #[Assert\Positive(message: "Le capacite ne peut pas être une valeur negative")]
    #[Assert\GreaterThanOrEqual(value: 20, message: "Le capacite doit être supérieur ou égal à 20")]
    #[Assert\LessThanOrEqual(value: 200, message: "Le capacite doit etre entre 20 et 200")]
    private ?int $capacite=null;

    #[ORM\Column]
    #[Groups("moyens")]
    #[Assert\NotBlank (message:" Le numero de transport ne peut pas être vide")]
    private ?string $nummoy=null;

    public function getIdmoy(): ?int
    {
        return $this->idmoy;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(string $matricule): self
    {
        $this->matricule = $matricule;

        return $this;
    }

    public function getCapacite(): ?int
    {
        return $this->capacite;
    }

    public function setCapacite(int $capacite): self
    {
        $this->capacite = $capacite;

        return $this;
    }

    public function getNummoy(): ?string
    {
        return $this->nummoy;
    }

    public function setNummoy(string $nummoy): self
    {
        $this->nummoy = $nummoy;

        return $this;
    }


}
