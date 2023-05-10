<?php

namespace App\Entity;

use App\Repository\ReclamationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ReclamationRepository::class)]
class Reclamation
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'integer')]
    #[Groups("reclams")]
    private ?int $idr;

    #[ORM\Column(type: 'integer',name:'idU')]
    private ?int $idU;

    #[ORM\Column(type: 'string', length: 100)]
    #[Groups("reclams")]
    private ?string $nom;

    #[ORM\Column(type: 'string', length: 100)]
    #[Groups("reclams")]
    private ?string $prenom;

    #[ORM\Column(type: 'datetime', options: ['default' => 'CURRENT_TIMESTAMP'])]
    #[Groups("reclams")]
    private ?\DateTimeInterface $dater;

    #[ORM\Column(type: 'text', length: 16777215)]
    #[Groups("reclams")]
    private ?string $descrec;

    #[ORM\OneToMany(mappedBy: 'idr', targetEntity: Repreclamation::class)]
private Collection $RepReclamation;
    // #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    // #[ORM\JoinColumn(name: 'idU', referencedColumnName: 'idU')]
    // private ?Utilisateur $idu;

    public function getIdr(): ?int
    {
        return $this->idr;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
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

    public function getDescrec(): ?string
    {
        return $this->descrec;
    }

    public function setDescrec(string $descrec): self
    {
        $this->descrec = $descrec;

        return $this;
    }


    
/**
 * @return Collection<int, Repreclamation>
 */
public function getRepReclamation(): Collection
{
    return $this->RepReclamation;
}

public function addRepReclamation(Repreclamation $RepReclamation): self
{
    if (!$this->RepReclamation->contains($RepReclamation)) {
        $this->RepReclamation->add($RepReclamation);
        $RepReclamation->setIdr($this);
    }

    return $this;
}

public function removeRepReclamation(Repreclamation $RepReclamation): self
{
    if ($this->RepReclamation->removeElement($RepReclamation)) {
        // set the owning side to null (unless already changed)
        if ($RepReclamation->getIdr() === $this) {
            $RepReclamation->setIdr(null);
        }
    }

    return $this;
}

    public function getIdu(): ?int
    {
        return $this->idU;
    }

    public function setIdu(?int $idu): self
    {
        $this->idU = $idu;

        return $this;
    }
}
