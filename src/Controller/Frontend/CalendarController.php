<?php

namespace App\Controller\Frontend;

use App\Entity\Calendar;
use App\Form\CalendarType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Symfony\Component\HttpFoundation\Request;
use App\Repository\CalendarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CalendarController extends AbstractController
{

    #[Route('/frontend', name: 'app_calendar_index')]
    public function index(CalendarRepository $calendarRepository): Response
    {
        
        return $this->render('frontend/index.html.twig', [
            'calendars' => $calendarRepository->findAll(),
        ]);
    }
    #[Route('/frontend', name: 'app_frontend')]
    public function index1(): Response
    {
        return $this->render('frontend/index.html.twig', [
            'controller_name' => 'FrontendController',
        ]);
    }

    #[Route('/list', name: 'app_calendar_index', methods: ['GET'])]
    public function list(CalendarRepository $calendarRepository): Response
    {
       //dd($stationRepository->findAll());
        return $this->render('frontend/calendar/index.html.twig', [
            'calendars' => $calendarRepository->findAll(),
        ]);
    }

    #[Route('/calendar', name: 'app_calendar')]
    public function Calendar(CalendarRepository $calendar): Response
    {
        $events = $calendar->findAll();

        $rdvs = [];

        foreach($events as $event){
            $rdvs[] = [
                'id' => $event->getId(),
                'start' => $event->getStart()->format('Y-m-d H:i:s'),
                'end' => $event->getEnd()->format('Y-m-d H:i:s'),
                'title' => $event->getTitle(),
                'description' => $event->getDescription(),
                'backgroundColor' => $event->getBackgroundColor(),
                'borderColor' => $event->getBorderColor(),
                'textColor' => $event->getTextColor(),
                'allDay' => $event->isAllDay(),
            ];
        }

        $data = json_encode($rdvs);

        return $this->render('frontend/calendar/calendar.html.twig', compact('data'));
    }
        
    
    
    
    #[Route('/frontend/calendar/new', name: 'app_calendar_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CalendarRepository $calendarRepository): Response
    {
        $calendar = new Calendar();
        $form = $this->createForm(CalendarType::class, $calendar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $calendarRepository->save($calendar, true);

            return $this->redirectToRoute('app_calendar', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->renderForm('frontend/calendar/new.html.twig', [
            'calendar' => $calendar,
            'form' => $form,
        ]);
    }

    #[Route('/frontend/show/{id}', name: 'app_calendar_show', methods: ['GET'])]
    public function show(Calendar $calendar): Response
    {
        return $this->render('frontend/calendar/show.html.twig', [
            'calendar' => $calendar,
        ]);
    }

    #[Route('/frontend/{id}/edit', name: 'app_calendar_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Calendar $calendar, CalendarRepository $calendarRepository): Response
    {
        $form = $this->createForm(CalendarType::class, $calendar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $calendarRepository->save($calendar, true);

            return $this->redirectToRoute('app_calendar_index1', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('frontend/calendar/edit.html.twig', [
            'calendar' => $calendar,
            'form' => $form,
        ]);
    }

    #[Route('/frontend/delete/{id}', name: 'app_calendar_delete', methods: ['POST'])]
    public function delete(Request $request, Calendar $calendar, CalendarRepository $calendarRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$calendar->getId(), $request->$request->get('_token'))) {
            $calendarRepository->remove($calendar, true);
        }

        return $this->redirectToRoute('app_calendar_index', [], Response::HTTP_SEE_OTHER);
    }
}


