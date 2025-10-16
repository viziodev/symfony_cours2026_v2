<?php

namespace App\Form;

use App\Dto\EmployeDto;
use App\Entity\Departement;
use App\Entity\Employe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomComplet')
            ->add('telephone')
            ->add('adresse')
           
            ->add('numero',null,[
                "mapped"=>false
            ])
           // ->add('embaucheAt', null, [
                //'widget' => 'single_text',
            //])
            ->add('departement', EntityType::class, [
                'class' => Departement::class,
                'choice_label' => 'name',
            ])
             ->add('isArchived',ChoiceType::class,[
                  'choices' => [
                       'Oui' => true,
                       'Non' => false,
                ],
                 'expanded' => true,    // radio buttons
                 'multiple' => false,
                 'label' => 'Archived',
                 'required' => false,
                 'data' => false, 
                 
               

            ])
            ->add('btnSave', SubmitType::class, [
                 'label' => 'Enregister',
                 "attr"=>[
                    "class"=>"btn btn-primary float-end",
                   
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EmployeDto::class,
        ]);
    }
}
