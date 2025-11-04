<?php

namespace App\Controller;

use App\Repository\EmployeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    public function __construct(private readonly  EmployeRepository $employeRepository)
    {
        
    }
    #[Route(path: '/', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {

        // if user is already logged in, redirect to some appropriate page
         if ($this->getUser()) {
            if($this->isGranted('ROLE_ADMIN')){
                return $this->redirectToRoute('app_departement_list');
            }
            $departementId=$this->employeRepository->findOneByEmail($this->getUser()->getUserIdentifier())->getDepartement()->getId();
             return $this->redirectToRoute('app_employe_list',["idDept"=>$departementId]);
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
