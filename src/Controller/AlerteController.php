<?php

namespace App\Controller;

use App\Entity\Alerte;
use App\Form\AlerteType;
use App\Repository\AlerteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/alerte')]
class AlerteController extends AbstractController
{



    #[Route('/alerte', name: 'app_alertes', methods: ['GET'])]
    public function frontIndex(AlerteRepository $alerteRepository, Environment $twig)
    {
        $alerts = $alerteRepository->findAll();
    
        $template = 'base-front.html.twig';
        $context = [
            'alerts' => $alerts
        ];
    
        $html = $twig->render($template, $context);
        return new Response($html);
    }
    #[Route('/', name: 'app_alerte_index', methods: ['GET'])]
    public function index(AlerteRepository $alerteRepository): Response
    {
        return $this->render('alerte/index.html.twig', [
            'alertes' => $alerteRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_alerte_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AlerteRepository $alerteRepository): Response
    {
        $alerte = new Alerte();
        $form = $this->createForm(AlerteType::class, $alerte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $alerteRepository->save($alerte, true);

            return $this->redirectToRoute('app_alerte_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('alerte/new.html.twig', [
            'alerte' => $alerte,
            'form' => $form,
        ]);
    }

    #[Route('/{idAlerteEve}', name: 'app_alerte_show', methods: ['GET'])]
    public function show(Alerte $alerte): Response
    {
        return $this->render('alerte/show.html.twig', [
            'alerte' => $alerte,
        ]);
    }

    #[Route('/{idAlerteEve}/edit', name: 'app_alerte_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Alerte $alerte, AlerteRepository $alerteRepository): Response
    {
        $form = $this->createForm(AlerteType::class, $alerte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $alerteRepository->save($alerte, true);

            return $this->redirectToRoute('app_alerte_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('alerte/edit.html.twig', [
            'alerte' => $alerte,
            'form' => $form,
        ]);
    }

    #[Route('/{idAlerteEve}', name: 'app_alerte_delete', methods: ['POST'])]
    public function delete(Request $request, Alerte $alerte, AlerteRepository $alerteRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$alerte->getIdAlerteEve(), $request->request->get('_token'))) {
            $alerteRepository->remove($alerte, true);
        }

        return $this->redirectToRoute('app_alerte_index', [], Response::HTTP_SEE_OTHER);
    }
}
