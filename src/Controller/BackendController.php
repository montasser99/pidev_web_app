<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BackendController extends AbstractController
{
    #[Route('/backend', name: 'app_back')]
    public function index(): Response
    {
        return $this->redirectToRoute("chart");
    
    }
    #[Route('/backend', name: 'app_backend')]
    public function index2(): Response
    {
        return $this->render('backend/back.html.twig', [
            'controller_name' => 'BackendController',
        ]);
    }

}