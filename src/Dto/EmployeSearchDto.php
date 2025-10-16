<?php 
namespace App\Dto;

use App\Entity\Departement;

class EmployeSearchDto{
    public ?Departement $departement=null ;
    public ?string $numero=null ;
    public bool $isArchived = false;

}