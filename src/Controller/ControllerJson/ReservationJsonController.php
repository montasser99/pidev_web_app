<?php

namespace App\Controller\ControllerJson;

use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ReservationJsonController extends AbstractController
{
    #[Route('/reservation/json', name: 'app_reservation_json')]
    public function index(ReservationRepository $reservationRepository,SerializerInterface $serializer ): Response
    {

$reservation = $reservationRepository->findAll();
$json = $serializer->serialize($reservation, 'json', ['groups' => "reserv"]);

return new Response($json);
    }
}
