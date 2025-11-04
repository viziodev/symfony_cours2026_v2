<?php

namespace App\Entity;

use App\Repository\EmployeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: EmployeRepository::class)]
class Employe extends User
{


    #[ORM\Column(length: 200)]
    #[Assert\NotBlank(message:"Le nom et le prenom de l'employe est obligatoire")]
    #[Assert\Length(
        min:4,
        max:25,
        minMessage:"Le  nom et le prenom  de l'employe doit avoir au moins {{ limit }} caracteres",
        maxMessage:"Le  nom et le prenom  de l'employe doit avoir au plus {{ limit }} caracteres"
    )]
    private ?string $nomComplet = null;

    #[ORM\Column(length: 25,unique:true)]
      #[Assert\NotBlank(message:"Le Telephone de l'employe est obligatoire")]
       #[Assert\Regex(
        pattern: "/^(77|78)\d{7}$/",
        message: "Le numéro de téléphone '{{ value }}' n'est pas valide. Il doit contenir 9 chiffres et peut commencer par 77 ou 78."
    )]
    private ?string $telephone = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $adresse = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updateAt = null;

    #[ORM\ManyToOne(inversedBy: 'employes')]
    #[ORM\JoinColumn(nullable: false)]

    #[Assert\NotNull(message:"Le departement de l'employe est obligatoire")]
    private ?Departement $departement = null;

    public function __construct()
    {
        $this->isArchived=false;
        $this->createAt=new \DateTimeImmutable();
    }
    #[ORM\Column]
    private ?bool $isArchived = null;

    #[ORM\Column(length: 20,unique:true)]
    private ?string $numero = null;

    #[ORM\Column(nullable: true)]
    #[Assert\LessThanOrEqual(
        'today',
         message: "La date d'embauche ne peut pas être superieur a la date du jour."
    )]
    private ?\DateTimeImmutable $embaucheAt = null;



    //nom de l'image 
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;

    //Champ du tampon non mappé a la base de donnees
    #[Assert\NotNull(message:"La photo de l'employe est obligatoire")]
    #[Assert\Image(
        maxSize: '2M',
        mimeTypes: ['image/jpeg', 'image/png'],
        mimeTypesMessage: 'Veuillez télécharger une image valide (JPEG ou PNG).',
        maxSizeMessage: 'La taille maximale du fichier  est de 2 Mo.'       
    )]
     private $photoFile;

    

    public function getNomComplet(): ?string
    {
        return $this->nomComplet;
    }

    public function setNomComplet(string $nomComplet): static
    {
        $this->nomComplet = $nomComplet;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeImmutable
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeImmutable $createAt): static
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeImmutable
    {
        return $this->updateAt;
    }

    public function setUpdateAt(?\DateTimeImmutable $updateAt): static
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    public function getDepartement(): ?Departement
    {
        return $this->departement;
    }

    public function setDepartement(?Departement $departement): static
    {
        $this->departement = $departement;

        return $this;
    }

    public function isArchived(): ?bool
    {
        return $this->isArchived;
    }

    public function setIsArchived(bool $isArchived): static
    {
        $this->isArchived = $isArchived;

        return $this;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): static
    {
        $this->numero = $numero;

        return $this;
    }

    public function getEmbaucheAt(): ?\DateTimeImmutable
    {
        return $this->embaucheAt;
    }

    public function setEmbaucheAt(?\DateTimeImmutable $embaucheAt): static
    {
        $this->embaucheAt = $embaucheAt;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): static
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get the value of photoFile
     */
    public function getPhotoFile()
    {
        return $this->photoFile;
    }

    /**
     * Set the value of photoFile
     */
    public function setPhotoFile($photoFile): self
    {
        $this->photoFile = $photoFile;

        return $this;
    }
}
