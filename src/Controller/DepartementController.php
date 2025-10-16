<?php

namespace App\Controller;

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
    public function list(Request $request ): Response
    {
        $departement=$this->departementRepository->find(1);
        $form=$this->createForm(DepartementType::class, $departement);
         $form->handleRequest($request);
         if($form->isSubmitted()){
            $this->manager->persist($departement);
           $this->manager->flush();
           return $this->redirectToRoute('app_departement_list');
         }
        $page=$request->query->get("page",1);
        $offset=($page-1)*self::LIMIT;
        $departements=$this->departementRepository->findBy([],[
            "id"=>"desc"
        ],self::LIMIT, $offset);
        $count =$this->departementRepository->count([]);
        $nbrePage=  ceil($count /self::LIMIT);
        return $this->render('departement/list.html.twig', [
            'departements' => $departements,
            "nbrePage"=>$nbrePage,
            "pageEncours"=>$page,
            "formDept"=>$form->createView()
        ]);
    }

   
}
