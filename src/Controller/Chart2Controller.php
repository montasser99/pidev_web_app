<?php

// src/Controller/ChartController.php

namespace App\Controller;

use App\Repository\AbonnementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Symfony\Component\HttpFoundation\Response;

class Chart2Controller extends AbstractController
{
    #[Route('/chart2', name: 'app_chart2')]
    public function index(AbonnementRepository $abonnementRepository) 
    {

       $nb_bus = $abonnementRepository->getNbBus();
       $nb_metro = $abonnementRepository->getNbMetro();
       $nb_train = $abonnementRepository->getNbTrain();
        return $this->render('chart2/index.html.twig', [
  
           'nb_bus' => $nb_bus,
           'nb_metro' => $nb_metro,
           'nb_train' => $nb_train,
        ]);
        
}
}
