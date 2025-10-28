<?php
namespace App\Services;
use Symfony\Component\HttpFoundation\Request;

interface PaginationService{
     public function getPageFromRequest(Request $request): int;
     public function createPaginationData(int $currentPage, int $totalPages): array;
}