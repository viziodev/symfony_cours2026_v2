<?php 
namespace App\Services;

use App\Dto\DepartementDto;
use App\Entity\Departement;

interface DepartementService{
public function  create(DepartementDto $dto): Departement;
public function  getPaginatedList(array $filters, int $page, int $limit): array;
public function  countDepartements(array $filters): int;
public function  calculateTotalPages(array $filters, int $limit): int;
}