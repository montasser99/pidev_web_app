<?php

namespace App\Controller;

use App\Entity\Moyenstransport;
use App\Form\MoyenstransportType;
use App\Repository\MoyenstransportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/moyenstransport')]
class MoyenstransportController extends AbstractController
{
    #[Route('/', name: 'app_moyenstransport_index', methods: ['GET'])]
    public function index(MoyenstransportRepository $moyenstransportRepository): Response
    {

        #dd($moyenstransportRepository->find(1)->getType());
        return $this->render('moyenstransport/index.html.twig', [
            'moyenstransports' => $moyenstransportRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_moyenstransport_new', methods: ['GET', 'POST'])]
    public function new(Request $request, MoyenstransportRepository $moyenstransportRepository): Response
    {
        $moyenstransport = new Moyenstransport();
        $form = $this->createForm(MoyenstransportType::class, $moyenstransport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $moyenstransportRepository->save($moyenstransport, true);

            $this->addFlash('notice', ' créer avec Succès !');
            return $this->redirectToRoute('app_moyenstransport_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('moyenstransport/new.html.twig', [
            'moyenstransport' => $moyenstransport,
            'form' => $form,
        ]);
    }

    #[Route('/{idmoy}', name: 'app_moyenstransport_show', methods: ['GET'])]
    public function show(Moyenstransport $moyenstransport): Response
    {
        return $this->render('moyenstransport/show.html.twig', [
            'moyenstransport' => $moyenstransport,
        ]);
    }

    #[Route('/{idmoy}/edit', name: 'app_moyenstransport_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Moyenstransport $moyenstransport, MoyenstransportRepository $moyenstransportRepository): Response
    {
        $form = $this->createForm(MoyenstransportType::class, $moyenstransport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $moyenstransportRepository->save($moyenstransport, true);

            $this->addFlash('notice', ' Modifié avec Succès !');
            return $this->redirectToRoute('app_moyenstransport_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('moyenstransport/edit.html.twig', [
            'moyenstransport' => $moyenstransport,
            'form' => $form,
        ]);
    }

    #[Route('/{idmoy}', name: 'app_moyenstransport_delete', methods: ['POST'])]
    public function delete(Request $request, Moyenstransport $moyenstransport, MoyenstransportRepository $moyenstransportRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $moyenstransport->getIdmoy(), $request->request->get('_token'))) {
            $moyenstransportRepository->remove($moyenstransport, true);
        }

        return $this->redirectToRoute('app_moyenstransport_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('rechercheM', name: 'app_recherche_moy')]
    public function rechercheM(Request $request, MoyenstransportRepository $moyenstransportRepository): Response
    {
        $filter = $request->query->get('filterM');

        $Moyens = $moyenstransportRepository->filterTransportByEnter($filter);
        
        return $this->render('moyenstransport/index.html.twig', [
            'moyenstransports' => $Moyens,
        ]);
    }
}
