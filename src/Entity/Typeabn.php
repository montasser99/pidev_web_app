<?php

namespace App\Entity;

use App\Repository\TypeabnRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeabnRepository::class)]
class Typeabn
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message:'champ vide')]
    private ?string $dureeA = null;

    #[ORM\Column(nullable: true)]
    #[Assert\NotNull(message:'champ vide')]
    private ?int $prixA = null;

    #[ORM\OneToMany(mappedBy: 'plan', targetEntity: Abonnement::class)]
    private Collection $abonnements;

    public function __construct()
    {
        $this->abonnements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDureeA(): ?string
    {
        return $this->dureeA;
    }

    public function setDureeA(?string $dureeA): self
    {
        $this->dureeA = $dureeA;

        return $this;
    }

    public function getPrixA(): ?int
    {
        return $this->prixA;
    }

    public function setPrixA(int $prixA): self
    {
        $this->prixA = $prixA;

        return $this;
    }

    /**
     * @return Collection<int, Abonnement>
     */
    public function getAbonnements(): Collection
    {
        return $this->abonnements;
    }

    public function addAbonnement(Abonnement $abonnement): self
    {
        if (!$this->abonnements->contains($abonnement)) {
            $this->abonnements->add($abonnement);
            $abonnement->setPlan($this);
        }

        return $this;
    }

    public function removeAbonnement(Abonnement $abonnement): self
    {
        if ($this->abonnements->removeElement($abonnement)) {
            // set the owning side to null (unless already changed)
            if ($abonnement->getPlan() === $this) {
                $abonnement->setPlan(null);
            }
        }

        return $this;
    }
    public function __toString(): string{
        return $this->dureeA . '(' . $this->prixA . ')';
    }
}
