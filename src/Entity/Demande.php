<?php

namespace App\Entity;
use App\Repository\DemandeRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: DemandeRepository::class)]
#[Vich\Uploadable]



class Demande
{
   
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer", name:'id')]
    #[Groups("demandes")]

   private  ?int $id=null;
    

    

    #[ORM\Column(length: 150 , name:'nomc' , type: 'string')]
    #[Assert\NotBlank (message:" Cdateamps obligatoire")]
    #[Groups("demandes")]
    private  ?string $nomc=null;

    

    #[ORM\Column(length: 150 , name:'moyen')]
    #[Assert\NotBlank (message:" Cdateamps obligatoire")]
    #[Groups("demandes")]
    private  ?string $moyen=null;

    

    
    #[ORM\Column(length: 150 , name:'dated')]
    #[Assert\NotBlank (message:" Champs obligatoire")]
    #[Groups("demandes")]
    private  ?string $dated=null;

    #[ORM\Column(length: 150 , name:'datea')]
    #[Assert\NotBlank (message:" Champs obligatoire")]
    #[Groups("demandes")]
    private  ?string $datea=null;
    

    #[ORM\Column(length: 150 , name:'permis')]
    #[Assert\NotBlank (message:" Champs obligatoire")]
    #[Groups("demandes")]
    private  ?string $permis=null;
    
   

     #[ORM\Column(length: 150 , name:'EmailC')]
     #[Assert\NotBlank (message:" Champs obligatoire")]
     #[Assert\Email(message: "L'adresse email '{{ value }}' n'est pas valide.")]
     #[Groups("demandes")]
    private  ?string $Emailc=null;

    #[Vich\UploadableField(mapping: 'permis_images', fileNameProperty: 'permis')]
    private ?File $imgFile = null;
   
    

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMoyen(): ?string
    {
        return $this->moyen;
    }

    public function setMoyen(string $moyen): self
    {
        $this->moyen = $moyen;

        return $this;
    }

    public function getDated(): ?string
    {
        return $this->dated;
    }

    public function setDated(string $dated): self
    {
        $this->dated = $dated;

        return $this;
    }

    public function getDatea(): ?string
    {
        return $this->datea;
    }

    public function setDatea(string $datea): self
    {
        $this->datea = $datea;

        return $this;
    }

    public function getPermis(): ?string
    {
        return $this->permis;
    }

    public function setPermis(?string $permis): self
    {
        $this->permis = $permis;

        return $this;
    }
   

    public function getEmailc(): ?string
    {
        return $this->Emailc;
    }

    public function setEmailc(string $Emailc): self
    {
        $this->Emailc = $Emailc;

        return $this;
    }

    public function setImgFile(?File $imgFile = null): void
    {
        $this->imgFile = $imgFile;

    }

    public function getImgFile(): ?File
    {
        return $this->imgFile;
    }



}
