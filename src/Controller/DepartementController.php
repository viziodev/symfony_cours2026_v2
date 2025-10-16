<?php

namespace App\Controller;

use App\Repository\DepartementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DepartementController extends AbstractController
{
   private const LIMIT=4;
    public function __construct(private readonly DepartementRepository $departementRepository)
    {
        
    }
    /*
        Liste des Departements ==>GET
        Creer un departement ==>POST(name)
     */
    #[Route('/departement/list', name: 'app_departement_list',methods:["GET","POST"])]
    public function list(Request $request): Response
    {
        $page=(int)$request->query->get("page",1);
        $offset=($page-1)*self::LIMIT;
        $filtre=[
            "isArchived"=>false
          ];
        $departements=$this->departementRepository->findBy($filtre,[],self::LIMIT, $offset);
        $nbrePage=ceil($this->departementRepository->count($filtre)/self::LIMIT);
        return $this->render('departement/list.html.twig', [
             'departements' => $departements,
             "pageEncours"=>$page,
             "nbrePage"=>$nbrePage
        ]);
    }

   
}
