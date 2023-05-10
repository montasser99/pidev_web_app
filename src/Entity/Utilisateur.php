<?php

namespace App\Entity;
use App\Entity\Adresse;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
#[Vich\Uploadable]
class Utilisateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank (message:'Ce Champ doit etre rempli')]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;
    #[ORM\Column(length: 255)]
    #[Assert\Regex("/^[a-zA-Z]+$/", message:'le nom doit etre une chaine de caracteres ')]
    #[Assert\NotNull (message:'ce champ ne doit pas etre null ')]
     private ?string $nomu = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank (message:'Ce Champ doit etre rempli ')]
    #[Assert\Regex("/^[a-zA-Z]+$/", message:'le prenom doit etre une chaine de caracteres ')]

    private ?string $prenomu = null;

    #[ORM\Column]
    #[Assert\NotBlank (message:'Ce Champ doit etre rempli ')]
    #[Assert\Regex("/^[0-9]{8}$/" , message:'numero doit contenir 8 champs')]

    private ?int $telephoneu = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank (message:'Ce Champ doit etre rempli ')]

    private ?string $roleu = null;

    #[ORM\Column(unique: true)]
    #[Assert\NotBlank (message:'Ce Champ doit etre rempli ')]
    private ?int $cinu = null;

    #[ORM\Column(length: 500, nullable: true)]
    #[Assert\NotBlank (message:'Ce Champ doit etre rempli ')]

    private ?string $imagepu = null;

    #[ORM\Column(nullable: true)]
    private ?bool $abonneu = null;

    #[ORM\ManyToOne(targetEntity: Adresse::class)]
    #[ORM\JoinColumn(name: 'idAdresse', referencedColumnName: 'id')]
    #[Assert\NotBlank (message:'Ce Champ doit etre rempli ')]
    private  $idadresse = null;
    
    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[Vich\UploadableField(mapping: 'user_image', fileNameProperty: 'imagepu')]
    //#[Assert\NotBlank (message:'Ce Champ doit etre rempli ')]
    private ?File $imgFile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $createdatu = null;


    const ROLE_ADMIN = 'ROLE_ADMIN';

    public function getNomu(): ?string
    {
        return $this->nomu;
    }

    public function setNomu(string $nomu): self
    {
        $this->nomu = $nomu;

        return $this;
    }

    public function getPrenomu(): ?string
    {
        return $this->prenomu;
    }

    public function setPrenomu(string $prenomu): self
    {
        $this->prenomu = $prenomu;

        return $this;
    }

    public function getTelephoneu(): ?int
    {
        return $this->telephoneu;
    }

    public function setTelephoneu(int $telephoneu): self
    {
        $this->telephoneu = $telephoneu;

        return $this;
    }

    public function getRoleu(): ?string
    {
        return $this->roleu;
    }

    public function setRoleu(string $roleu): self
    {
        $this->roleu = $roleu;

        return $this;
    }

    public function getCinu(): ?int
    {
        return $this->cinu;
    }

    public function setCinu(int $cinu): self
    {
        $this->cinu = $cinu;

        return $this;
    }

    public function getImagepu(): ?string
    {
        return $this->imagepu;
    }

    public function setImagepu(?string $imagepu): self
    {
        $this->imagepu = $imagepu;

        return $this;
    }

    public function isAbonneu(): ?bool
    {
        return $this->abonneu;
    }

    public function setAbonneu(?bool $abonneu): self
    {
        $this->abonneu = $abonneu;

        return $this;
    }

    public function getIdadresse(): ?Adresse
    {
        return $this->idadresse;
    }

    public function setIdadresse(?Adresse $idadresse): self
    {
        $this->idadresse = $idadresse;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }
    public function isAdmin(): bool
    {
        return in_array(self::ROLE_ADMIN, $this->getRoles());
    }

    public function getCreatedatu(): ?string
    {
        return $this->createdatu;
    }

    public function setCreatedatu(?string $createdatu): self
    {
        $this->createdatu = $createdatu;

        return $this;
    }
}
