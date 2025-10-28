<?php

namespace App\Repository;

use App\Entity\Departement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Departement>
 */
class DepartementRepository extends ServiceEntityRepository implements RepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Departement::class);
    }
  public function findPaginated(array $filters, int $page, int $limit): array
    {
        $offset = ($page - 1) * $limit;
        return $this->findBy(
            $filters,
            ['id' => 'DESC'],
            $limit,
            $offset
        );
    }

    /**
     * Compte les départements selon les filtres
     */
    public function countByFilters(array $filters): int
    {
        return $this->count($filters);
    }
}
