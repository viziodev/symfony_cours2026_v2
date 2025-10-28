<?php

namespace App\Form;

use App\Entity\Departement;
use App\Entity\Employe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
             ->add('numero', TextType::class, [
                'label' => 'Numéro Employé',
                'attr' => [
                    'readonly' => true,
                ],
             ])
             ->add('nomComplet',TextType::class, [
                'label' => 'Nom  et Prenom',
                 'required'=>false,
             ])
             ->add('telephone',TextType::class,[
                   'required'=>false,
             ])
              ->add('embaucheAt', DateType::class, [
                 'widget' => 'single_text',
                   'required'=>false,
              ])
             ->add('departement', EntityType::class, [
                'class' => Departement::class,
                'choice_label' => 'name',
            ])
              ->add('isArchived',ChoiceType::class, [
                'label' => 'Archiver',
                  'choices'  => [
                     'Non' => false,
                     'Oui' => true
                    
                  ],
                   "expanded" => true,
                   'data' => false
                ])
            ->add('adresse',TextareaType::class, [
                'required' => false,
                "attr" => [
                    'rows' => 4,
                ],
             ])
          
            ->add("btnSaveDept",SubmitType::class,[
                "label"=>"Enregistrer l'Employe",
                "attr"=>[
                    "class"=>"btn btn-primary float-end"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employe::class,
             "attr"=>[
                 "data-turbo"=>'false'
             ]
        ]);
    }
}
