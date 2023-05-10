<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/reservation')]
class ReservationController extends AbstractController
{
    #[Route('/', name: 'app_reservation_index', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository): Response
    {
        // dd($reservationRepository->findAll());
        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservationRepository->findAll(),
        ]);
    }

    #[Route('/rechercheR', name: 'app_recherche_res')]
    public function recherchea(Request $request, ReservationRepository $ReservationRepository): Response
    {
        $filter = $request->query->get('filter');
        $reserv=$ReservationRepository->filterReservationByEnter($filter);
    
        return $this->render('reservation/index.html.twig', [
            'reservations' => $reserv,
        ]);
    }
    
}
