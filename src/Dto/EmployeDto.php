<?php 
namespace App\Dto;

use App\Entity\Departement;
use App\Entity\Employe;
use Symfony\Component\Validator\Constraints as Assert;

class EmployeDto{
  
    public ?int $id = null;
     #[Assert\NotBlank(message: 'Le nom est obligatoire')]
     #[Assert\Length( 
        min: 2,
        max: 100,
        minMessage: 'Le nom doit contenir au moins {{ limit }} caractères',
        maxMessage: 'Le nom doit contenir au plus {{ limit }} caractères'
    )]
    public string $nomComplet;
     #[Assert\NotBlank(message: 'Le numéro de téléphone est obligatoire')]
     #[Assert\Regex(
        pattern: '/^77|76|78{1}[0-9]{7}$/',
        message: 'Le numéro de téléphone n\'est pas valide'
     )]
    public string $telephone;
    public string $adresse;
     #[Assert\NotNull(message: 'Le département est obligatoire')]
    public Departement $departement;
    public string $numero ;
    #[Assert\LessThanOrEqual(
        'today',
        message: 'La date d\'embauche ne peut pas être dans le futur'
    )]
    public \DateTimeImmutable $embaucheAt;
     #[Assert\Type(
        type: 'bool',
        message: 'Le champ isArchived doit être un booléen'
    )]
    public bool $isArchived = false;

    #[Assert\Length(max: 255)]
     private ?string $photo = null;


    public function toEntity(): Employe
    {
        $employe = new Employe();
        $employe->setNomComplet($this->nomComplet);
        $employe->setTelephone($this->telephone);
        $employe->setAdresse($this->adresse);
        $employe->setDepartement($this->departement);
        $employe->setNumero($this->numero);
        $employe->setEmbaucheAt($this->embaucheAt);
        $employe->setIsArchived($this->isArchived);
        return $employe;
    }
    

}