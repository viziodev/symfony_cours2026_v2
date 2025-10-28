<?php

namespace App\Controller;

use App\DTO\EmployeSearchFormDto;
use App\Entity\Employe;
use App\Form\EmployeType;
use App\Repository\DepartementRepository;
use App\Repository\EmployeRepository;
use App\Service\GenerateNumeroService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EmployeController extends AbstractController
{
   private const LIMIT=10;
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
             $searchFormDto=new EmployeSearchFormDto();
             $form=$this->createForm(\App\Form\EmployeSearchType::class, $searchFormDto);

          $departement=null;
          $filtre=[
            "isArchived"=>false
          ];
          if ($idDept!=null) {
              $filtre["departement"]=$idDept;
              $departement=$this->departementRepository->find($idDept);

          }
           $page=$request->query->get("page",1);
           $offset=($page-1)*self::LIMIT;

          $count =$this->employeRepository->count($filtre);
          $nbrePage=  ceil($count /self::LIMIT);
          $employes=$this->employeRepository->findBy($filtre,[
            "id"=>"desc"
          ],self::LIMIT, $offset);
         
        return $this->render('employe/list.html.twig', [
            'employes' =>  $employes,
            "departement"=>$departement,
            "nbrePage"=>$nbrePage,
            "pageEncours"=>$page,
            "formSearchEmp"=>$form->createView()
        ]);
    }

     /*
        Creer un Employe ==>POST(name)
     */

     #[Route('/employe/add', name: 'app_employe_add',methods:["GET","POST"])]
     public function save(Request $request,GenerateNumeroService $numService): Response
     {
        $employe=new Employe();
        $employe->setNumero($numService->generateNumeroCompte());
        $form=$this->createForm(EmployeType::class, $employe);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
           $this->employeRepository->save($employe, true);
           $this->addFlash('success',"Employe ajouté avec succès");
            return $this->redirectToRoute('app_employe_list');

        }

         return $this->render('employe/form.html.twig', [
             'formEmp' => $form->createView()
         ]);
     }
}
