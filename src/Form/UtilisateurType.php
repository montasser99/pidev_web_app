<?php

namespace App\Form;

use App\Entity\Adresse;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('roles')
            ->add('password')
            ->add('nomu')
            ->add('prenomu')
            ->add('telephoneu', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le champ telephone ne peut pas être vide et doit contenir 8 chiffres.',
                    ]),
                ],
            ])
            
             ->add('roleu', ChoiceType::class, [
                 'choices' => [
                     'admin' => 'Admin',
                    'client' => 'Client',
                    'Chauffeur'=>'Chauffeur'
                ],
                'placeholder' => '',
                'required' => true, // set required option to true
                'constraints' => [
                    new NotBlank([
                        'message' => 'Il faut preciser le role de utilisateur ',
                    ]),
                ],
            ])
                
             
          
            ->add('cinu')
 
            // ->add('imagepu', FileType::class, [
            //     'label' => 'Only type images',

            //     // unmapped means that this field is not associated to any entity property
            //     'mapped' => false,

            //     // make it optional so you don't have to re-upload the PDF file
            //     // every time you edit the Product details
            //     'required' => false,

            //     // unmapped fields can't define their validation using annotations
            //     // in the associated entity, so you can use the PHP constraint classes
            //     'constraints' => [
            //         new File([
            //             'maxSize' => '1024k',
            //             'mimeTypes' => [
            //                 'image/*',
            //             ],
            //             'mimeTypesMessage' => 'Please upload a valid image document',
            //         ])
            //     ],
            // ])
            ->add('roles', CollectionType::class, [
                'entry_type' => ChoiceType::class,
                'entry_options' => [
                    'choices' => [
                        'Administrator' => 'ROLE_ADMIN',
                        'User' => 'ROLE_USER',
                    ],
                ],
                'allow_add' => true,
                'prototype' => true,
                'by_reference' => false,
            ])
            ->add('imagepu', FileType::class, [
                'data_class' => null,

                'label' => 'Profile Picture',
                'required' => false,
                'mapped' => true,
            ])
    
            
            ->add('abonneu')
            // ->add('idadresse')
            ->add('idadresse', EntityType::class, [
            'attr' => ['class' => 'form-control form-group'],
            'label'=> false,
            'class' => Adresse::class,

            'choice_label' =>  function (Adresse $adresse) {
                return sprintf('%s %s %s %s', $adresse->getRegion(), $adresse->getCite(),$adresse->getRue(),$adresse->getNumposte());
            },
            'placeholder' => 'Choisissez une adresse', 
        ])
        // ->add('save',SubmitType::class)    
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
