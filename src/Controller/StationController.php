<?php

namespace App\Controller;

use App\Entity\Station;
use App\Form\StationType;
use App\Repository\StationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/station')]
class StationController extends AbstractController
{
    #[Route('/list', name: 'app_station_index', methods: ['GET'])]
    public function index(StationRepository $stationRepository): Response
    {
       //dd($stationRepository->findAll());
        return $this->render('station/indexB.html.twig', [
            'stations' => $stationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_station_new', methods: ['GET', 'POST'])]
    public function new(Request $request, StationRepository $stationRepository): Response
    {
        $station = new Station();
        $form = $this->createForm(StationType::class, $station);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $existingStation = $stationRepository->findOneBy([
                'noms' => $station->getNomS(),
                'adresse' => $station->getAdresse(),
                'idcircuit' => $station->getIdcircuit()
            ]);
            if ($existingStation) {
                $this->addFlash('danger', 'Circuit already exist ! !');
                return $this->redirectToRoute('app_station_new');
            }
            $stationRepository->save($station, true);
            $this->addFlash('success', 'Station added successfully !');
            return $this->redirectToRoute('app_station_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('station/new.html.twig', [
            'station' => $station,
            'form' => $form,
        ]);
    }

    #[Route('/{idstation}', name: 'app_station_show', methods: ['GET'])]
    
    #[ParamConverter('station', class:"App\Entity\Station")]
    public function show(Station $station): Response
    {
        return $this->render('station/show.html.twig', [
            'station' => $station,
        ]);
    }

    #[Route('/{idstation}/edit', name: 'app_station_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Station $station, StationRepository $stationRepository): Response
    {
        $form = $this->createForm(StationType::class, $station);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $stationRepository->save($station, true);
            $this->addFlash('success', 'Station updated successfully !');
            return $this->redirectToRoute('app_station_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('station/edit.html.twig', [
            'station' => $station,
            'form' => $form,
        ]);
    }

    #[Route('/{idstation}', name: 'app_station_delete', methods: ['POST'])]
    public function delete(Request $request, Station $station, StationRepository $stationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$station->getIdstation(), $request->request->get('_token'))) {
            $stationRepository->remove($station, true);
        }
        $this->addFlash('success', 'Station deleted successfully !');

        return $this->redirectToRoute('app_station_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/search/aa', name: 'search_app', methods: ['GET'])]
    public function search(Request $request, StationRepository $stationRepository): Response
    {
   
      $query = $request->query->get('query');
      $stations = $stationRepository->search($query);
    
    
        return $this->render('station/indexB.html.twig', [
            'stations' => $stations,
        ]);
    }
}
