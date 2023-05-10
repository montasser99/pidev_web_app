<?php

namespace App\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontendController extends AbstractController
{
    #[Route('/frontend', name: 'app_frontend')]
    public function index(): Response
    { $hasAccess = $this->isGranted('ROLE_USER');
        $this->denyAccessUnlessGranted('ROLE_USER');
        return $this->render('frontend/index.html.twig', [
            'controller_name' => 'FrontendController',
        ]);
    }
}
