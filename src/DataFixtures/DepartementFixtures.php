<?php

namespace App\DataFixtures;

use App\Entity\Departement;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DepartementFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        
     for ($i=1; $i <=10; $i++) { 
          $data=new Departement();
          $data->setName("Departement".$i);
          $data->setIsArchived($i%2==0);
          $manager->persist( $data);
      }
        $manager->flush();
    }
}
