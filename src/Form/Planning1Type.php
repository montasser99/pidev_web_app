<?php

namespace App\Form;

use App\Entity\Circuit;
use App\Entity\Moyenstransport;
use App\Entity\Planning;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class Planning1Type extends AbstractType
{    

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
       
        $builder
        
        ->add('idcir', EntityType::class, [
            'attr' => ['class' => 'form-control form-group'],
            'label'=> false,
            'class' => Circuit::class,
            'choice_label' =>  function (Circuit $circuit) {
                return sprintf('%s %s', $circuit->getDepartc(), $circuit->getArriveec());
            },
            'placeholder' => 'Choisissez un circuit', 
        ]) 
        ->add('idmoy', EntityType::class, [
            'label'=> false,
            'class' => Moyenstransport::class,
            'choice_label' =>  function (Moyenstransport $moyens) {
            return sprintf('%s N° %s', $moyens->getType(), $moyens->getNummoy());
            },
            'placeholder' => 'Choisissez un moyen de transport',
        ])      

                 
            ->add('dated', TimeType::class, [
                'widget' => 'single_text',
                'input' => 'string',
                'with_seconds' => true,
                'attr' => [
                    'min' => '05:00:00',
                    'max' => '21:00:00',
                    
                ],
            ])
            ->add('datea', TimeType::class, [
                'widget' => 'single_text',
                'input' => 'string',
                'with_seconds' => true,
                'attr' => [
                    'min' => '05:00:00',
                    'max' => '21:00:00',
                ],
            ])
            ->add('prix')
            ;
            
            }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Planning::class,
        ]);
    
    }
}
