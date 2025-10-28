<?php

namespace App\Form;

use App\Dto\EmployeDto;
use App\Entity\Departement;
use App\Entity\Employe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class EmployeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
           ->add('numero',null,[
                "attr"=>[
                   "readonly"=>true,
                    "class"=>"form-control",
                ]
            ])
            ->add('nomComplet',TextType::class,[
                "required"=>false,
            ])
            ->add('telephone',TextType::class,[
                "required"=>false,
            ])
          
            ->add('embaucheAt', DateType::class, [
                'widget' => 'single_text',
                'html5' => true,  // Utilise le datepicker natif du navigateur
                'attr' => [
                    'class' => 'form-control',
                    'data-turbo' => 'false', // si tu veux désactiver Turbo
                ],
           ])
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
             ->add('photo', FileType::class, [
                    'label' => 'Photo de profil',
                    'mapped' => false, // on ne lie pas directement au champ de l'entité
                    'required' => false, // facultatif
                    'attr' => [
                        'accept' => 'image/*', // limite aux fichiers image
                        'data-turbo' => 'false', // si tu utilises Turbo
                    ],
                    'constraints' => [
                        new Assert\File([
                            'maxSize' => '2M',
                            'mimeTypes' => [
                                'image/jpeg',
                                'image/png',
                            ],
                            'mimeTypesMessage' => 'Veuillez uploader une image au format JPG, PNG ou WEBP.',
                        ]),
                    ],
                 ])

              ->add('adresse',TextareaType::class,[
                "required"=>false,
                "attr"=>[
                    "class"=>"form-control",
                    "rows"=>4,
                ]
              ])
              ->add('btnSave', SubmitType::class, [
                 'label' => 'Enregister',
                 "attr"=>[
                    "class"=>"btn btn-primary float-end",
                    "enctype"=>"multipart/form-data",
                   
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EmployeDto::class,
            'attr' => [
               'data-turbo' => 'false',
           ],
        ]);
    }
}
