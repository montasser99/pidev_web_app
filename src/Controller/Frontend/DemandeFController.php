<?php

namespace App\Controller\Frontend;

use Twilio\Exceptions\TwilioException;
use Twilio\Http\HttpClient;
use Twilio\Rest\Api\V2010\Account\MessageList;
use Twilio\Rest\Api\V2010\Account\Twilio\NumberList;
use Twilio\Rest\Client as TwilioClient;
use App\Entity\Circuit;
use App\Entity\Demande;
use App\Entity\Moyenstransport;
use App\Entity\Planning;
use App\Form\DemandeType;
use App\Form\PlanningDetailsType;
use App\Form\PlanningType;
use App\Repository\PlanningRepository;
use App\Repository\DemandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/demande')]
class DemandeFController extends AbstractController

    {
       

    #[Route('/frontend/demande/new', name: 'app_demande_new', methods: ['GET' , 'POST'])]
    public function new(Request $request, DemandeRepository $demandeRepository , PlanningRepository $planningRepository): Response
    {
        $demande = new Demande();
        $planning = new Planning();
      //  $planningDetails = $planningRepository->getPlanningDetails();
       // dd($planningDetails);
       
        $form = $this->createForm(DemandeType::class,$demande);
       // dd($request->get('dated'));  
        $form->handleRequest($request);
     
        if ($form->isSubmitted()) {
            $imageFile = $form->get('permis')->getData();

            if ($imageFile) {
                // Set the image name as the current timestamp and the original file extension
                $imageName = time() . '.' . $imageFile->getClientOriginalExtension();

                // Move the file to the configured directory using VichUploader
                $imageFile->move(
                    $this->getParameter('permis_images'),
                    $imageName
                );

                // Update the item entity with the new image filename

                $demande->setPermis($imageName);
            }
            $demande->setEmailc($this->getUser()->getEmail());
            $planning = $form->get('nomc')->getData();

            $demande->setDatea($form->get('datea')->getData()->getDatea());
            $demande->setDated($form->get('dated')->getData()->getDated());
            $demandeRepository->save($demande, true);
            $this->addFlash('success', 'asked added successfully');
            return $this->redirectToRoute('app_demande_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('frontend/demande/new.html.twig', [
            'demande' => $demande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_demande_show', methods: ['GET'])]
    public function show(Demande $demande): Response
    {
        return $this->render('demande/showB.html.twig', [
            'demande' => $demande,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_demande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Demande $demande, DemandeRepository $demandeRepository): Response
    {
        $form = $this->createForm(DemandeType::class, $demande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $demandeRepository->save($demande, true);

            return $this->redirectToRoute('app_demande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('demande/edit.html.twig', [
            'demande' => $demande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_demande_delete', methods: ['POST'])]
    public function delete(Request $request, Demande $demande, DemandeRepository $demandeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$demande->getId(), $request->request->get('_token'))) {
            $demandeRepository->remove($demande, true);
        }

        return $this->redirectToRoute('app_demande_index', [], Response::HTTP_SEE_OTHER);
    }


   
}


