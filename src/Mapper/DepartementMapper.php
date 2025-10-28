<?php
namespace App\Mapper;
use App\Dto\DepartementDto;
use App\Entity\Departement;

class DepartementMapper{
     public function toDto(Departement $departement, ?int $nbreEmployes = null): DepartementDto
     {
            $dto = new DepartementDto();
            $dto->id = $departement->getId();
            $dto->name = $departement->getName();
            $dto->createAt = $departement->getCreateAt();
            $dto->archived = $departement->isArchived();
            $dto->nbreEmployes = $nbreEmployes ?? $departement->getEmployes()->count();
            return $dto;
    }

    public function toDtoCollection(array $results): array
    {
        return array_map(
            fn($result) => $this->toDto($result[0], (int)$result['nbreEmployes']),
            $results
        );
    }

    /**
     * Convertit un tableau d'entités simples en DTOs
     * 
     * @param Departement[] $departements
     * @return DepartementDto[]
     */
    public function toDtoArray(array $departements): array
    {
        return array_map(
            fn(Departement $dept) => $this->toDto($dept),
            $departements
        );
    }
}