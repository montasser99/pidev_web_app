<?php

namespace App\Form;

use App\Entity\Evenement;
use App\Entity\Offre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

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
       
        ->add('dateFinEve', null, [
            'widget' => 'single_text',
        ])
        ->add('prix')
        ->add('image',FileType::class, [
            'label' => 'image',
            'mapped' => false,
            'required' => false,
            // 'constraints' => [
            //     new NotNull(),
            //  /*   new Image([
            //         'maxSize' => '5M',
            //         'mimeTypes' => ['image/jpeg', 'image/png', 'image/jpg'],
            //     ]),*/
            // ],
            'constraints' => [
                new File([
                    'maxSize' => '2048k',
                    'mimeTypes' => [
                        'image/*',
                    ],
                    'mimeTypesMessage' => 'Please upload a valid image',
                ])
            ],
        ])
        ->add('save',SubmitType::class)

         //EntityType::class,[
            //'class'=>Offre::class,
            //'choice_label'=>true,
            //'expanded'=>true,
            //'multiple'=>false
        //)
        //->add('save',SubmitType::class)
    ;
}




           
    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}
