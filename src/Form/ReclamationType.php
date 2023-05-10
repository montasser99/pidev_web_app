<?php

namespace App\Form;

use App\Entity\Reclamation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;

class ReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', null, [
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Le nom ne peut pas être vide.']),
                    new Regex(['pattern' => '/^[a-zA-Z ]+$/', 'message' => 'Le nom doit contenir uniquement des lettres.']),
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('prenom', null, [
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Le prénom ne peut pas être vide.']),
                    new Regex(['pattern' => '/^[a-zA-Z ]+$/', 'message' => 'Le prénom doit contenir uniquement des lettres.']),
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('dater', DateType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'La date ne peut pas être vide.']),
                ],
                'data' => new \DateTime(),
                'attr' => [
                    'class' => 'form-control js-datetimepicker',
                    'min' => (new \DateTime())->format('Y-m-d'),
                ],
                'widget' => 'single_text',
            ])
            ->add('descrec', null, [
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'La description ne peut pas être vide.']),
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
        ]);
    }
}