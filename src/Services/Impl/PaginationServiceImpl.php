<?php

namespace App\Services\Impl;

use App\Services\PaginationService;
use Symfony\Component\HttpFoundation\Request;

class PaginationServiceImpl implements PaginationService{
    public function getPageFromRequest(Request $request): int
    {
        $page = $request->query->getInt('page', 1);
        return max(1, $page);
    }

    public function createPaginationData(int $currentPage, int $totalPages): array
    {
        return [
            'pageEncours' => $currentPage,
            'nbrePage' => $totalPages
        ];
    }
}