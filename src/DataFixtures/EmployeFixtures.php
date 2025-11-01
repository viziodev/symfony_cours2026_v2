<?php

namespace App\DataFixtures;

use App\Entity\Employe;
use App\Repository\DepartementRepository;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class EmployeFixtures extends Fixture
{

    public function __construct(private readonly DepartementRepository $departementRepository,
                                private readonly UserPasswordHasherInterface $passwordHasher)
    {
        
    }
    public function load(ObjectManager $manager): void
    {

        $departements=$this->departementRepository->findAll();
        foreach ($departements as $key => $departement) {
              for ($i=1; $i <=20 ; $i++) { 
               
                 $data=new Employe();
                 $data->setNomComplet("Employe".$key."".$i);
                 $data->setTelephone("Telephone".$key."".$i);
                 $data->setDepartement($departement);
                 $data->setNumero("Num".$key."".$i);
                 $date = new \DateTimeImmutable('2025-10-01');
                 $newDate = $date->add(new \DateInterval('P'.$i.'D'));
                 $data->setEmbaucheAt( $newDate);
                 $data->setIsArchived($i%2==0);
                 $data->setEmail("employe".$key."".$i."@example.com");
                 $hashedPassword = $this->passwordHasher->hashPassword(
                     $data,
                   "passer123"
                );
                 $data->setPassword($hashedPassword );
                 $i==1? $data->setRoles(["ROLE_AMIN"]): $data->setRoles(["ROLE_USER"]);
                 $manager->persist($data);
              }
        }

        $manager->flush();
    }
}
