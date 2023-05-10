<?php

namespace App\Form;

use App\Entity\Alerte;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AlerteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('typeAlerteEve' , ChoiceType::class,[
                'choices' => [
                    'aaaa' => 'aaaa',
                    'bbbb' => 'bbbb',
                    'xcxx'=> 'xcxx',
                    'xxxx'=> 'xxxx'
                ]
                ])
            ->add('titreEve')
            ->add('descEve', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'class' => 'form-control', // Ajouter des classes CSS pour personnaliser le style
                    'rows' => 4, // Nombre de lignes de la zone de texte
                ],
            ])
            ->add('dateDebEve', null, [
                'widget' => 'single_text',
            ])
            ->add('dateFinEve')
            ->add('dateFinEve', null, [
                'widget' => 'single_text',
            ])
            ->add('save',SubmitType::class)
            ;
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Alerte::class,
        ]);
    }
}
