<?php

namespace App\Form;

use App\DTO\EmployeSearchFormDto;
use App\Entity\Departement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
           ->add('numero', TextType::class, [
                'label' => 'Numéro Employé',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Rechercher par numéro...',
                    "class"=>'form-control',
                    'autocomplete' => 'off',
                  
                ],
             ])
              ->add('isArchived',ChoiceType::class, [
                'label' => 'Archiver',
                 "attr"=>[
                    "class"=>'form-select'
                 ],
                  'choices'  => [
                     'Actif' => false,
                     'Archiver' => true
                  ],
                   'data' => false
                ])
             ->add('departement', EntityType::class, [
                'class' => Departement::class,
                'choice_label' => 'name',
                'data' => $options['departement_default'] ?? null,
              ])
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EmployeSearchFormDto::class,
            'departement_default'=> null,
             "attr"=>[
                 "data-turbo"=>'false'
             ]
        ]);
    }
}
