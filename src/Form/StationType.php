<?php

namespace App\Form;

use App\Entity\Circuit;
use App\Entity\Station;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        
        ->add('nomS', TextType::class, [
            'required' => true, // set required option to true
            'constraints' => [
           new NotBlank([
               'message' => 'champs obligatoire.',
           ]),
       ],
           ])
        
        ->add('adresse', TextType::class, [
            'required' => true, // set required option to true
           
           ])
    
        ->add('idcircuit', EntityType::class, [
            'label'=> false,
            'class' => Circuit::class,
            'choice_label' =>  function (Circuit $circuit) {
                return sprintf('%s', $circuit->getNomc());
            },
            'placeholder' => 'Choisissez un circuit',
            
        ])
    ;
}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Station::class,
        ]);
    }
}
