<?php

namespace App\Controller;

use App\Entity\Typeabn;
use App\Form\Typeabn1Type;
use App\Repository\TypeabnRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/typeabn')]
class TypeabnController extends AbstractController
{
    #[Route('/', name: 'app_typeabn_index', methods: ['GET'])]
    public function index(TypeabnRepository $typeabnRepository): Response
    {
        return $this->render('typeabn/index.html.twig', [
            'typeabns' => $typeabnRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_typeabn_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TypeabnRepository $typeabnRepository): Response
    {
        $typeabn = new Typeabn();
        $form = $this->createForm(Typeabn1Type::class, $typeabn);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $typeabnRepository->save($typeabn, true);
           // $this->addFlash('success', 'Ajout avec succès');
            return $this->redirectToRoute('app_typeabn_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('typeabn/new.html.twig', [
            'typeabn' => $typeabn,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_typeabn_show', methods: ['GET'])]
    public function show(Typeabn $typeabn): Response
    {
        return $this->render('typeabn/show.html.twig', [
            'typeabn' => $typeabn,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_typeabn_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Typeabn $typeabn, TypeabnRepository $typeabnRepository): Response
    {
        $form = $this->createForm(Typeabn1Type::class, $typeabn);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $typeabnRepository->save($typeabn, true);

            return $this->redirectToRoute('app_typeabn_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('typeabn/edit.html.twig', [
            'typeabn' => $typeabn,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_typeabn_delete', methods: ['POST'])]
    public function delete(Request $request, Typeabn $typeabn, TypeabnRepository $typeabnRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$typeabn->getId(), $request->request->get('_token'))) {
            $typeabnRepository->remove($typeabn, true);
        }

        return $this->redirectToRoute('app_typeabn_index', [], Response::HTTP_SEE_OTHER);
    }
}
