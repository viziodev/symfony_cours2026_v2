<?php 
namespace App\DTO   ;

use App\Entity\Departement;

class EmployeSearchFormDto 
{
    public ?string $numero = null;
    public ?bool $isArchived = null;  
    public ?Departement $departement = null;

}            