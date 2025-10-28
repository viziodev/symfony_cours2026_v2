<?php

namespace App\Controller;

use App\DTO\DepartementListDto;
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
    //Nombre d'element par page
    private const LIMIT=4;
    public function __construct(private readonly DepartementRepository $departementRepository)
    {
        
    }
    /*
        Liste des Departements ==>GET
        Creer un departement ==>POST(name)
     */
    #[Route('/departement/list', name: 'app_departement_list',methods:["GET","POST"])]
    public function list(Request $request ): Response
    {
        //Creation entity associe  au formulaire
         $departement=new Departement();

         //Creation du formulaire format objet
         $form=$this->createForm(DepartementType::class, $departement);

         //Traitement de la requete
            //1-Rempli le formulaire avec les donnees de la requete
            //2-Rempli l'entite $departement avec les donnees du formulaire
         $form->handleRequest($request);
         if($form->isSubmitted() && $form->isValid()){
              $this->departementRepository->save($departement,true);
               $this->addFlash('success',"Departement ajouté avec succès");
              return $this->redirectToRoute('app_departement_list');
         }


        $page=$request->query->get("page",1);
        $offset=($page-1)*self::LIMIT;
        //Entites
         $departements=$this->departementRepository->findBy([],[
            "id"=>"desc"
         ],self::LIMIT, $offset);
        //DTOs
          $departementsDto=DepartementListDto::fromEntities($departements);
            $count =$this->departementRepository->count([]);
            $nbrePage=  ceil($count /self::LIMIT);
            return $this->render('departement/list.html.twig', [
                'departements' => $departementsDto,
                "nbrePage"=>$nbrePage,
                "pageEncours"=>$page,
                "formDept"=>$form->createView() //format html
            ]);
    }

   
}
