<?php

namespace App\Controller;

use App\Repository\DepartementRepository;
use App\Repository\EmployeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EmployeController extends AbstractController
{
       private const LIMIT=4;

    public function __construct(private readonly EmployeRepository $employeRepository,
                                private readonly DepartementRepository $departementRepository)
    {
        
    }
    /*
        Liste des Employes ==>GET
        Creer un departement ==>POST(name)
         path: /employe/list/{idDept}   
                Url : http://127.0.0.1:8000/employe/list/1
        path: /employe/list/{idDept?}   
                Url : http://127.0.0.1:8000/employe/list
                      http://127.0.0.1:8000/employe/list/1
     */
    #[Route('/employe/list/{idDept?}', name: 'app_employe_list')]
    public function list($idDept,Request $request): Response
    { 
          $departement=null;
          $page=(int)$request->query->get("page",1);
          $offset=($page-1)*self::LIMIT;
          $filtre=[
            "isArchived"=>false
          ];
          if ($idDept!=null) {
              $filtre["departement"]=$idDept;
              $departement=$this->departementRepository->find($idDept);

          }
           $employes=$this->employeRepository->findBy($filtre,[],self::LIMIT, $offset);
           $nbrePage=ceil($this->departementRepository->count($filtre)/self::LIMIT);
        return $this->render('employe/list.html.twig', [
            'employes' =>  $employes,
            "departement"=>$departement,
             "pageEncours"=>$page,
             "nbrePage"=>$nbrePage
        ]);
    }

     /*
        Creer un Employe ==>POST(name)
     */

     #[Route('/employe/add', name: 'app_employe_add')]
     public function save(): Response
     {
         return $this->render('employe/form.html.twig', [
             'controller_name' => 'EmployeController',
         ]);
     }
}
