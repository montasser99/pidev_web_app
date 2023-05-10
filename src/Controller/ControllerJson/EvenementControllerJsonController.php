<?php

namespace App\Controller\ControllerJson;

use App\Entity\Evenement;
use App\Repository\EvenementRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EvenementControllerJsonController extends AbstractController
{
    #[Route('/mobile/all', name: 'showall_mobile', methods: ['POST'])]
    public function showall(EvenementRepository $evenementRepository): JsonResponse
    {
        $evenements = $evenementRepository->findAll();
        $data = [];
    
        foreach ($evenements as $evenement) {
            $data[] = [
                'idEve' => $evenement->getIdEve(),
                'titreEve' => $evenement->getTitreEve(),
                'descEve' => $evenement->getDescEve(),
                'dateDebEve' => $evenement->getDateDebEve()->format('Y-m-d'),
                'dateFinEve' => $evenement->getDateFinEve()->format('Y-m-d'),
                'offreId' => $evenement->getOffreId(),
                'prix' => $evenement->getPrix(),
                'image' => $evenement->getImage(),
                'participations' => $evenement->getParticipations(),
            ];
        }
    
        return new JsonResponse($data);
    }
    
    
    
    #[Route('/mobile/add', name: 'app_evenement_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $evenement = new Evenement();
        $evenement->setTitreEve($request->get('titreEve'));
        $evenement->setDescEve($request->get('descEve'));
        $evenement->setDateDebEve(new \DateTime($request->get('dateDebEve')));
        $evenement->setDateFinEve(new \DateTime($request->get('dateFinEve')));
        $evenement->setPrix($request->get('prix'));
        $evenement->setImage($request->get('image'));
    
        $entityManager->persist($evenement);
        $entityManager->flush();
    
        return new JsonResponse(['status' => 'Evenement created']);
    
    }
    
    
    #[Route('/mobile/edit/{id}', name: 'app_evenement_update', methods: ['POST'])]
    public function update(Request $request, Evenement $evenement): JsonResponse
    {
        $evenement->setTitreEve($request->get('titreEve'));
        $evenement->setDescEve($request->get('descEve'));
        $evenement->setDateDebEve(new \DateTime($request->get('dateDebEve')));
        $evenement->setDateFinEve(new \DateTime($request->get('dateFinEve')));
        $evenement->setOffreId($request->get('offreId'));
        $evenement->setPrix($request->get('prix'));
        $evenement->setImage($request->get('image'));
    
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
    
        return $this->json([
            'message' => 'Event updated successfully!',
        ]);
    }
    
    #[Route('/mobile/delete/{id}', name: 'app_evenement_delete_mobile', methods: ['DELETE'])]
    public function deleteevent(Request $request, Evenement $evenement): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
    
        // delete all related participations
        $participations = $evenement->getParticipations();
        foreach ($participations as $participation) {
            $entityManager->remove($participation);
        }
        
    
        $entityManager->remove($evenement);
        $entityManager->flush();
    
        return $this->json([
            'message' => 'Event deleted successfully!',
        ]);
    }
    
}
