<?php

namespace App\Controller;

use App\DTO\DepartementListDto;
use App\DTO\EmployeListDto;
use App\DTO\EmployeSearchFormDto;
use App\Entity\Employe;
use App\Form\EmployeType;
use App\Repository\DepartementRepository;
use App\Repository\EmployeRepository;
use App\Service\GenerateNumeroService;
use App\Service\Impl\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

final class EmployeController extends AbstractController
{
  
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
    #[Route('/employe/list/{idDept?}', name: 'app_employe_list',methods:["GET","POST"])]
    public function list($idDept,Request $request,SessionInterface $session): Response
    { 
          //Filtre par defaut
             $departement=null;
             $filtre=[
              "isArchived"=>false
             ];
              // Récupération de la page courante
              $page = $request->query->get("page", 1);
               if ($idDept!=null) {
                    $filtre["departement"]=$idDept;
                    $departement=$this->departementRepository->find($idDept);
              }
              $searchFormDto=new EmployeSearchFormDto();
              $form=$this->createForm(\App\Form\EmployeSearchType::class, $searchFormDto,[
                 'method' => 'GET', // Changé en GET pour maintenir les filtres dans l'URL
                 'departement_default'=>$departement,
                 'csrf_protection' => false, // Désactivation du token CSRF

              ]);
               $form->handleRequest($request);
               if ( $form->isSubmitted() ) {

                     // Réinitialiser la page à 1 lors d'une nouvelle recherche
                        if ($searchFormDto->numero!=null) {
                        $filtre["numero"]=$searchFormDto->numero;
                        }
                        $filtre["departement"]=$searchFormDto->departement;
                        $filtre["isArchived"]=$searchFormDto->isArchived;
               }
            
               // Calcul de la pagination
               $offset = ($page - 1) * $this->getParameter('LIMIT_PER_PAGE');
               $count = $this->employeRepository->count($filtre);
               $nbrePage = ceil($count / $this->getParameter('LIMIT_PER_PAGE'));

               // Validation de la page
                 if ($page > $nbrePage && $nbrePage > 0) {
                      $page = $nbrePage;
                     $offset = ($page - 1) * $this->getParameter('LIMIT_PER_PAGE');
                     }
               // Récupération des employés avec pagination
               $employes = $this->employeRepository->findBy(
                  $filtre,
                  ["id" => "desc"],
                 $this->getParameter('LIMIT_PER_PAGE'),
                  $offset
              );
  
      // Conversion en DTO
      $employesDto = EmployeListDto::fromEntities($employes);
      $departementDto = $departement != null ? DepartementListDto::fromEntitie($departement) : null;

      return $this->render('employe/list.html.twig', [
         'employes' => $employesDto,
         'departement' => $departementDto,
         'nbrePage' => $nbrePage,
         'pageEncours' => $page,
         'formSearchEmp' => $form->createView(),
      ]);
    }

     /*
        Creer un Employe ==>POST(name)
     */

     #[Route('/employe/add', name: 'app_employe_add',methods:["GET","POST"])]
     public function save(Request $request,GenerateNumeroService $numService,  FileUploader $fileUploader): Response
     {
        $employe=new Employe();
        $employe->setNumero($numService->generateNumeroCompte());
        $form=$this->createForm(EmployeType::class, $employe);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
          //Champs mappés $form->getData() remplit l'entité $employe
          //Chmaps personnalisés ou non mappés
             $pays=$form->get("pays")->getData();
             $ville=$form->get("ville")->getData();
             $rue=$form->get("pays")->getData();
             $employe->setAdresse("Rue: $rue - Ville: $ville - Pays: $pays");
             $photoFile = $form->get('photoFile')->getData();
            
              if ($photoFile) {
                try {
                     $photoFileName = $fileUploader->upload($photoFile);
                     $employe->setPhoto($photoFileName);
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Erreur lors de l\'upload de la photo');
                }
            }

             $this->employeRepository->save($employe, true);
             $this->addFlash('success',"Employe ajouté avec succès");
             return $this->redirectToRoute('app_employe_list');
        }

         return $this->render('employe/form.html.twig', [
             'formEmp' => $form->createView()
         ]);
     }
}
