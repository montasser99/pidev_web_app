<?php

namespace App\Form;

use App\Entity\Calendar;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CalendarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title' ,TextType::class, [
           
                'required' => true, // set required option to true
                
               ])
            
            ->add('start' , DateTimeType::class, [
                'date_widget' => 'single_text',
                'required' => true
            ])
            ->add('end' , DateTimeType::class, [
                'date_widget' => 'single_text',
                'required' => true
            ])
            ->add('description' ,TextType::class, [
           
                'required' => true, // set required option to true
                
               ])
            ->add('all_day' ,CheckboxType::class, [
           
                'required' => true, // set required option to true
                
               ])
            ->add('background_color' , ColorType::class , [
                'required' => true,
            ])
            ->add('border_color' , ColorType::class , [
                'required' => true,
            ])
            ->add('text_color' , ColorType::class , [
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Calendar::class,
        ]);
    }
}
