<?php

namespace App\Form;

use App\Entity\Abonnement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;



class Abonnement1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
           // ->add('idU')
           ->add('moyenTransportA', ChoiceType::class, [
            'choices' => [
                'Train' => 'Train',
                'Bus' => 'Bus',
                'Metro' => 'Metro',
            ],
            'placeholder' => '',
            'required' => true, // set required option to true
            'constraints' => [
                new NotBlank([
                    'message' => 'le champ moyen de transport ne peut pas être vide.',
                ]),
            ],
        ])
       
            ->add('dateA', DateType::class, [
                'data' => new \DateTime(),
                
            ])
           // ->add('dateExpA')
           ->add('etudiantA', ChoiceType::class, [
            'choices' => [
                'Not Etudiant' => 0,
                'Etudiant' => 1,
            ],
            'expanded' => true,
            'multiple' => false,
        ])
          //  ->add('redEtA')
          //  ->add('plan' )
          ->add('plan', null, [
            'constraints' => [
                new NotBlank([
                    'message' => 'Le champ plan ne peut pas être vide.',
                ]),
            ],
        ])
        // ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
        //     $abonnement = $event->getData();
        //     $form = $event->getForm();
            
        //     // calculate dateExpA based on the selected plan
        //     $plan = $form->get('plan')->getData();
        //     dump($plan); // add this line to check the value of $plan
        //     $now = new \DateTime();
        //     if ($plan->getId() === 1) {
        //         $dateExpA = $now->add(new \DateInterval('P1M')); // add 1 month
        //     } elseif ($plan->getId() === 2) {
        //         $dateExpA = $now->add(new \DateInterval('P6M')); // add 6 months
        //     } else {
        //         $dateExpA = $now->add(new \DateInterval('P1Y')); // add 1 year
        //     }
            
        //     dump($dateExpA); // add this line to check the value of $dateExpA
            
        //     // set the calculated dateExpA on the Abonnement object
        //     $abonnement->setDateExpA($dateExpA);
        // });
        
        



            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Abonnement::class,
        ]);
    }
}
