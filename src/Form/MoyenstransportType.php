<?php

namespace App\Form;

use App\Entity\Moyenstransport;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MoyenstransportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('type', ChoiceType::class, [
            'choices' => [
                'Bus' => 'Bus',
                'Metro' => 'Metro',
                'Train' => 'Train',
            ],
        ])
            ->add('matricule')
            ->add('capacite')
            ->add('nummoy')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Moyenstransport::class,
        ]);
    }
}
