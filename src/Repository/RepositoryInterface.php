<?php 
namespace App\Repository;
interface RepositoryInterface{
    public function findPaginated(array $filters, int $page, int $limit): array;
    public function countByFilters(array $filters): int;
} 