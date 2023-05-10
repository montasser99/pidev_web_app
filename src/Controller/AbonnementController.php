<?php

namespace App\Controller;

use App\Entity\Abonnement;
use App\Form\Abonnement1Type;
use App\Repository\AbonnementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;



#[Route('/abonnement')]
class AbonnementController extends AbstractController
{
    #[Route('/', name: 'app_abonnement_index', methods: ['GET'])]
    public function index(AbonnementRepository $abonnementRepository): Response
    {
        return $this->render('abonnement/index.html.twig', [
            'abonnements' => $abonnementRepository->findAll(),
        ]);
    }


    #[Route('/new', name: 'app_abonnement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AbonnementRepository $abonnementRepository): Response
    {
        $abonnement = new Abonnement();
        $form = $this->createForm(Abonnement1Type::class, $abonnement);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Get the plan id selected from the form data
            $plan = $form->get('plan')->getData();
            
            // Calculate the expiration date based on the Plan object
            $dateExpA = new \DateTime();
            switch ($plan->getId()) {
                case '1':
                    $dateExpA->add(new \DateInterval('P1M'));
                    break;
                case '2':
                    $dateExpA->add(new \DateInterval('P6M'));
                    break;
                case '3':
                    $dateExpA->add(new \DateInterval('P1Y'));
                    break;
                default:
                    // Handle the case where an invalid plan id is selected
                    throw new \Exception('Invalid plan selected');
            }
            
            // Set the expiration date on the Abonnement object
            $abonnement->setDateExpA($dateExpA);
            $abonnement->setIdU($this->getUser()->getId());
            // Save the Abonnement object to the repository
            $abonnementRepository->save($abonnement, true);
            
            // Redirect to the new Abonnement form with a success message
            $this->addFlash('success', 'Ajout avec succès');
            return $this->redirectToRoute('app_abonnement_new', [], Response::HTTP_SEE_OTHER);
        }
    
        // Render the new Abonnement form
        return $this->renderForm('abonnement/new.html.twig', [
            'abonnement' => $abonnement,
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'app_abonnement_show', methods: ['GET'])]
    public function show(Abonnement $abonnement): Response
    {
        return $this->render('abonnement/show.html.twig', [
            'abonnement' => $abonnement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_abonnement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Abonnement $abonnement, AbonnementRepository $abonnementRepository): Response
    {
        $form = $this->createForm(Abonnement1Type::class, $abonnement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $abonnementRepository->save($abonnement, true);
            $this->addFlash('success', 'Modifiée avec succès');
 
            return $this->redirectToRoute('app_abonnement_edit', ['id' => $abonnement->getId()], Response::HTTP_SEE_OTHER);

        }

        return $this->renderForm('abonnement/edit.html.twig', [
            'abonnement' => $abonnement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_abonnement_delete', methods: ['POST'])]
    public function delete(Request $request, Abonnement $abonnement, AbonnementRepository $abonnementRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$abonnement->getId(), $request->request->get('_token'))) {
            $abonnementRepository->remove($abonnement, true);
        }
        //$this->addFlash('success', 'Ajout avec succès');
        return $this->redirectToRoute('app_abonnement_index', [], Response::HTTP_SEE_OTHER);
    }
        // Recherche
 

        
        
        
}
