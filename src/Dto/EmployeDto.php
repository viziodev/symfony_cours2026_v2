<?php 
namespace App\Dto;
class EmployeDto{
    public ?int $id = null;
    public string $nomComplet ;
    public string $telephone ;
    public string $adresse;
    public DepartementDto $departement ;
    public ?string $numero=null ;
    public \DateTimeImmutable $embaucheAt ;
    public bool $isArchived = false;

}