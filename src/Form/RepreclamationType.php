<?php

namespace App\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\NotBlank;
use App\Entity\Repreclamation;
use Symfony\Component\Validator\Constraints\Length;
use App\Entity\Reclamation;
use Symfony\Component\Form\Extension\Core\Type\LengthType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class RepreclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('NomAg', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer le nom de l\'agent.',
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 255,
                        'minMessage' => 'Le nom de l\'agent doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le nom de l\'agent doit contenir au maximum {{ limit }} caractères.',
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            // ->add('dater', DateType::class, [
            //     'required' => true,
            //     'constraints' => [
            //         new NotBlank(['message' => 'La date ne peut pas être vide.']),
            //     ],
            //     'data' => new \DateTime(),
            //     'attr' => [
            //         'class' => 'form-control js-datetimepicker',
            //         'min' => (new \DateTime())->format('Y-m-d'),
            //     ],
            //     'widget' => 'single_text',
            // ])
            ->add('daterep', DateType::class, [
                'data'   => new \DateTime(),
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control js-datetimepicker',
                    'min' => (new \DateTime())->format('Y-m-d'),
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer une date de réponse valide.',
                    ]),
                ],
                'html5' => false, // to avoid HTML5 validation messages in unsupported browsers
            ])
            ->add('reponse', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer la réponse à la réclamation.',
                    ]),
                    new Length([
                        'min' => 10,
                        'max' => 1000,
                        'minMessage' => 'La réponse doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'La réponse doit contenir au maximum {{ limit }} caractères.',
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 5,
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Repreclamation::class,
            'error_mapping' => [
                'daterep' => 'daterep',
                'NomAg' => 'NomAg',
            ],
        ]);
    }
}
