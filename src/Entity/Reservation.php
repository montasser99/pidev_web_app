<?php

namespace App\Entity;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReservationRepository;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $idnum = null;

    #[ORM\Column(type: "datetime")]
    private ?DateTime $dater = null ;

    #[ORM\Column(type: "datetime")]
    private ?DateTime $heuredep = null;

    #[ORM\Column(type: "datetime")]
    private ?DateTime $heurearr = null;

    #[ORM\Column(type: "string", length: 255)]
    private ?string $type = null;

    #[ORM\Column(type: "integer")]
    private ?int $cin = null;

    #[ORM\Column(type: "integer")]
    private ?int $prix = null;

    #[ORM\Column(type: "string", length: 255)]
    private ?string $numerot = null;

    #[ORM\Column(type: "datetime")]
    private ?DateTime $datereservation = null;


    public function getIdnum(): ?int
    {
        return $this->idnum;
    }

    public function getDater(): ?\DateTimeInterface
    {
        return $this->dater;
    }

    public function setDater(\DateTimeInterface $dater): self
    {
        $this->dater = $dater;

        return $this;
    }

    public function getHeuredep(): ?\DateTimeInterface
    {
        return $this->heuredep;
    }

    public function setHeuredep(\DateTimeInterface $heuredep): self
    {
        $this->heuredep = $heuredep;

        return $this;
    }

    public function getHeurearr(): ?\DateTimeInterface
    {
        return $this->heurearr;
    }

    public function setHeurearr(\DateTimeInterface $heurearr): self
    {
        $this->heurearr = $heurearr;

        return $this;
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

    public function getCin(): ?int
    {
        return $this->cin;
    }

    public function setCin(int $cin): self
    {
        $this->cin = $cin;

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

    public function getNumerot(): ?string
    {
        return $this->numerot;
    }

    public function setNumerot(string $numerot): self
    {
        $this->numerot = $numerot;

        return $this;
    }

    public function getDateReservation(): ?\DateTimeInterface
    {
        return $this->datereservation;
    }

    public function setDateReservation(\DateTimeInterface $DateReservation): self
    {
        $this->datereservation = $DateReservation;

        return $this;
    }

}
