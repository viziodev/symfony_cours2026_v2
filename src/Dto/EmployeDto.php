<?php 
namespace App\Dto;

use App\Entity\Departement;


class EmployeDto{
    public ?int $id = null;
    public string $nomComplet ;
    public string $telephone ;
    public string $adresse;
    public Departement $departement ;
    public ?string $numero=null ;
    public \DateTimeImmutable $embaucheAt ;
    public bool $isArchived = false;


    

}