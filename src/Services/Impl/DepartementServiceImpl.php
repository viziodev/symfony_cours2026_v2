<?php 
namespace App\Services\Impl;

use App\Dto\DepartementDto;
use App\Entity\Departement;
use App\Mapper\DepartementMapper;
use App\Repository\DepartementRepository;
use App\Services\DepartementService;
use Doctrine\ORM\EntityManagerInterface;

class DepartementServiceImpl implements DepartementService{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly DepartementRepository $repository,
        private readonly DepartementMapper $departementMapper
    ) {}
  public function create(DepartementDto $dto): Departement
    {
        $departement = new Departement();
        $departement->setName($dto->name);
        $this->entityManager->persist($departement);
        $this->entityManager->flush();
        return $departement;
    }

    /**
     * Récupère les départements paginés
     */
    public function getPaginatedList(array $filters, int $page, int $limit): array
     {
         $departements= $this->repository->findPaginated($filters, $page, $limit);
         return $this->departementMapper->toDtoArray( $departements);

    }

    /**
     * Compte le nombre total de départements
     */
    public function countDepartements(array $filters): int
    {
        return $this->repository->countByFilters($filters);
    }

    

    /**
     * Calcule le nombre de pages
     */
    public function calculateTotalPages(array $filters, int $limit): int
    {
        $total = $this->countDepartements($filters);
        return (int) ceil($total / $limit);
    }
}