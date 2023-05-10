<?php

namespace App\Form;

use App\Entity\Typeabn;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class Typeabn1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dureeA', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le champ durée ne peut pas être vide.',
                    ]),
                ],
            ])
            ->add('prixA', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le champ prix ne peut pas être vide.',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Typeabn::class,
        ]);
    }
}
