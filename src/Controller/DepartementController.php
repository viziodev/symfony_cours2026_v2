<?php

namespace App\Controller;

use App\Repository\DepartementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DepartementController extends AbstractController
{

    public function __construct(private readonly DepartementRepository $departementRepository)
    {
        
    }
    /*
        Liste des Departements ==>GET
        Creer un departement ==>POST(name)
     */
    #[Route('/departement/list', name: 'app_departement_list',methods:["GET","POST"])]
    public function list(): Response
    {
        $departements=$this->departementRepository->findAll();
        return $this->render('departement/list.html.twig', [
            'departements' => $departements,
        ]);
    }

   
}
