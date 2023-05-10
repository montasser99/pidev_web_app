<?php

namespace App\Form;

use App\Entity\Circuit;
use App\Entity\Demande;
use App\Entity\Moyenstransport;
use App\Entity\Planning;
use App\Repository\PlanningRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use PhpParser\Node\Expr\AssignOp\Plus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class DemandeType extends AbstractType
{       
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
                   

        ->add('nomc', EntityType::class, [
            'label' => false,
            'class' => Planning::class,
            'choice_label' => 'nomc',
            'placeholder' => 'Choisir un circuit',
        ])
        ->add('moyen', ChoiceType::class, [
            'choices' => [
               'Train' => 'Train',
               'Bus' => 'Bus',
               'Metro' => 'Metro',
            ],
            'label' => false,
            'placeholder' => 'Choisir un moyen de transport',
         ])
               
            ->add('dated', EntityType::class, [
                'label' => false,
                'class' => Planning::class,
                'choice_label' => 'dated',
                'placeholder' => 'Choisir une heure de départ',
            ])
            ->add('datea', EntityType::class, [
                'label' => false,
                'class' => Planning::class,
                'choice_label' => 'datea',
                'placeholder' => 'Choisir une heure d arrivée',
                ])
            ->add('permis', FileType::class, [
                'required' => true,
                'data_class' => null,
            ])
            ->add('EmailC', TextType::class, [
                'required' => false,
            ])
          
           
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Demande::class,
        ]);
    }
}

