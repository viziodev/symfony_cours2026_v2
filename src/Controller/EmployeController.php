<?php

namespace App\Controller;

use App\Dto\EmployeDto;
use App\Dto\EmployeSearchDto;
use App\Form\EmployeSearchType;
use App\Form\EmployeType;
use App\Repository\DepartementRepository;
use App\Repository\EmployeRepository;
use App\Services\GenerateNumero;
use App\Services\UploadPhotoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EmployeController extends AbstractController
{
       private const LIMIT=20;

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
             $filtre=[
                 "isArchived"=>false
             ];
             if ($idDept!=null) {
                $filtre["departement"]=$idDept;
                $departement=$this->departementRepository->find($idDept);

            }
            $dataForm = new EmployeSearchDto();
            $form = $this->createForm(EmployeSearchType::class, $dataForm,[
                     'default_departement' => $departement
              ]);
            $form->handleRequest($request);
            $page = max(1, (int)$request->query->get('page', 1));
            $offset = ($page - 1) * self::LIMIT;

            if ($form->isSubmitted()) {
                if ($dataForm->isArchived !== null) {
                    $filtre['isArchived'] = $dataForm->isArchived;
                }
                if (!empty($dataForm->numero)) {
                    $filtre['numero'] = $dataForm->numero;
                }
                if ($dataForm->departement !== null) {
                    $filtre['departement'] = $dataForm->departement; 
                }
            
            }
            $employes = $this->employeRepository->findBy($filtre, [
                'createAt' => 'DESC'
            ], self::LIMIT, $offset);
            $nbrePage = ceil($this->employeRepository->count($filtre) / self::LIMIT);
            return $this->render('employe/list.html.twig', [
              'employes' => $employes,
              'pageEncours' => $page,
              'nbrePage' => $nbrePage,
              'formSearchEmp' => $form->createView(),
               "departement"=> $departement
            ]);

          }


     /*
        Creer un Employe ==>POST(name)
     */

     #[Route('/employe/add', name: 'app_employe_add')]
     public function save(Request $request,GenerateNumero $faker,UploadPhotoService $file): Response
     {
               $dataForm=new EmployeDto();
               $dataForm->numero=$faker->generate();
               $form=$this->createForm(EmployeType::class,$dataForm);
               $form->handleRequest($request);
           if ( $form->isSubmitted() && $form->isValid()) {
                /** @var EmployeDto $data */
                $data = $form->getData();
                  $employe = $data->toEntity();
                  $photoFile = $form->get('photo')->getData();
                  if ($photoFile) {
                    $uploadDir = $this->getParameter('photos_directory');
                    $photoFilename = $file->uploadPhoto($uploadDir, $photoFile);
                    $employe->setPhoto($photoFilename);
                   }

                  $this->employeRepository->save($employe,true);
                  $this->addFlash('success', 'Employé créé avec succès.');
                  return $this->redirectToRoute('app_employe_list');
           }
          return $this->render('employe/form.html.twig', [
              'formEmp'=> $form->createView()
          ]);
     }
}
