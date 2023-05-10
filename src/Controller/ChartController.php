<?php

// src/Controller/ChartController.php

namespace App\Controller;

use App\Repository\AbonnementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Symfony\Component\HttpFoundation\Response;

class ChartController extends AbstractController
{
    #[Route('/chart', name: 'chart')]
    public function index(AbonnementRepository $abonnementRepository) 
    {
        $nb_m = $abonnementRepository->getNbM();
        $nb_s = $abonnementRepository->getNbS();
        $nb_a = $abonnementRepository->getNbA();
       // $nb_bus = $abonnementRepository->getNbBus();
      //  $nb_metro = $abonnementRepository->getNbMetro();
      //  $nb_train = $abonnementRepository->getNbTrain();
        return $this->render('chart/index.html.twig', [
            'nb_m'=>$nb_m,
            'nb_s'=>$nb_s,
            'nb_a'=>$nb_a,
           // 'nb_bus' => $nb_bus,
          //  'nb_metro' => $nb_metro,
          //  'nb_train' => $nb_train,
        ]);
        
}

}



