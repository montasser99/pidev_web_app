<?php
namespace App\EventListener;

use DateTime;
use App\Repository\PlanningRepository;
use Symfony\Component\HttpKernel\Event\ControllerEvent;

class MyCustomListener
{
    private $planningRepository;

    public function __construct(PlanningRepository $planningRepository)
    {
        $this->planningRepository = $planningRepository;
    }

    public function onKernelController(ControllerEvent $event)
    {
        $current_time = new DateTime();
        $current = $current_time->format('H:i:s');

        foreach ($this->planningRepository->findall() as $f) {
            $dateaDateTime = DateTime::createFromFormat('H:i:s', $f->getDatea());
            $dateArriver = $dateaDateTime->format('H:i:s');

            if ($current > $dateArriver) {
                $this->planningRepository->UpdateNbplaceToCapacite($f->getDated(), $f->getIdcir()->getIdcircuit(), $f->getIdmoy()->getIdmoy(), $f->getIdmoy()->getCapacite());
            }
        }
    }
}
?>