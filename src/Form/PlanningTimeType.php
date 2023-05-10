<?php

namespace App\Form;

use App\Entity\Planning;
use App\Repository\PlanningRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlanningTimeType extends AbstractType
{
    private $planningRepository;

    public function __construct(PlanningRepository $planningRepository)
    {
        $this->planningRepository = $planningRepository;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Planning::class,
            'hours' => range(0, 23),
            'minutes' => range(0, 59),
            'seconds' => range(0, 59),
            'with_seconds' => false,
        ]);
    }

    public function getParent()
    {
        return TimeType::class;
    }
}
