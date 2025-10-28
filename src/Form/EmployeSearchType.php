<?php

namespace App\Form;

use App\Dto\EmployeSearchDto;
use App\Entity\Departement;
use App\Entity\Employe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('isArchived',ChoiceType::class,[
                "choices"=>[
                    "Actif"=>false,
                    "Archiver"=>true,
                ],
                "expanded"=>false,
                "data"=>false
            ])
            ->add('numero')
            ->add('departement', EntityType::class, [
                 'class' => Departement::class,
                 'choice_label' => 'name',
                 "required"=>false,
                 'data' => $options['default_departement'], 
                 
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EmployeSearchDto::class,
            'default_departement' => null, 
        ]);
    }
}
