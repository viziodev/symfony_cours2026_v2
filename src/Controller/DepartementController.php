<?php

namespace App\Controller;

use App\Dto\DepartementDto;
use App\Entity\Departement;
use App\Form\DepartementType;
use App\Repository\DepartementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DepartementController extends AbstractController
{
   private const LIMIT=4;
    public function __construct(private readonly DepartementRepository $departementRepository,
                                private readonly EntityManagerInterface $manager)
    {
        
    }
    /*
        Liste des Departements ==>GET
        Creer un departement ==>POST(name)
     */
    #[Route('/departement/list', name: 'app_departement_list',methods:["GET","POST"])]
    public function list(Request $request): Response
    {
        //Formulaire
           $dataForm=new DepartementDto();
           $form=$this->createForm(DepartementType::class,$dataForm);
           $form->handleRequest($request);
           $page=(int)$request->query->get("page",1);
           if ( $form->isSubmitted() && $form->isValid()) {
                $entity =new Departement();
                $entity->setName($dataForm->name);
                $this->manager->persist($entity);
                $this->manager->flush();
                return $this->redirectToRoute('app_departement_list');
           }

          //Liste + Filtre
             $filtre=[
                "isArchived"=>false
             ];
           $offset=($page-1)*self::LIMIT;
           $departements=$this->departementRepository->findBy($filtre,["id"=>"desc"],self::LIMIT, $offset);
           $nbrePage=ceil($this->departementRepository->count($filtre)/self::LIMIT);
        return $this->render('departement/list.html.twig', [
             'departements' => $departements,
             "pageEncours"=>$page,
             "nbrePage"=>$nbrePage,
             'formDept'=> $form->createView()
        ]);
    }

   
}
