<?php

namespace App\Controller;

use App\Dto\DepartementDto;

use App\Form\DepartementType;
use App\Services\DepartementService;
use App\Services\PaginationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DepartementController extends AbstractController
{

   
     private const ITEMS_PER_PAGE = 4;
    public function __construct(
        private readonly DepartementService $departementService,
        private readonly PaginationService $paginationService,
    ) {}
    /*
        Liste des Departements ==>GET
        Creer un departement ==>POST(name)
     */
    #[Route('/departement/list', name: 'app_departement_list',methods:["GET","POST"])]
    public function list(Request $request): Response
    {
         $form = $this->createForm(DepartementType::class, new DepartementDto());
         $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var DepartementDto $data */
                $this->departementService->create($data);
                $this->addFlash('success', 'Département créé avec succès.');
          }
        // Récupération du numéro de page
         $page = $this->paginationService->getPageFromRequest($request);
         // Récupération des départements
           $filters = ['isArchived' => false];
          $departements = $this->departementService->getPaginatedList(
            $filters,
            $page,
            self::ITEMS_PER_PAGE
          );

        // Calcul de la pagination 
          $totalPages = $this->departementService->calculateTotalPages(
            $filters,
            self::ITEMS_PER_PAGE
         );
        // Préparation des données de pagination
        $paginationData = $this->paginationService->createPaginationData($page, $totalPages);
        return $this->render('departement/list.html.twig', [
            'departements' => $departements,
            'formDept' => $form->createView(),
            ...$paginationData
        ]);
    }

   
}
