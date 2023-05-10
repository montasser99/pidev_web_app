<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontendController extends AbstractController
{
    #[Route('/frontend', name: 'app_frontend')]
    public function index(): Response
    {
        return $this->render('frontend/front.html.twig', [
            'controller_name' => 'FrontendController',
        ]);
    }
    
}
