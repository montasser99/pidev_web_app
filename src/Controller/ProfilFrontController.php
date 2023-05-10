<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
class ProfilFrontController extends AbstractController
{
    #[Route('/profilFront', name: 'profilFront')]
    public function index(): Response
    {
        return $this->render('ProfilFront.html.twig', [
            'controller_name' => 'ProfilFrontController',
        ]);
    }
}