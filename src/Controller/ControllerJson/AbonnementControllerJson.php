<?php

namespace App\Controller\ControllerJson;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Abonnement;

use App\Form\Abonnement1Type;
use Symfony\Component\Serializer\Serializer;
use App\Repository\AbonnementRepository;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class AbonnementControllerJson extends AbstractController
{
    #[Route('/Abonnement/allJSON', name: 'app_mobile_index')]
  //  #[Groups(['Abn'])]
    public function index(AbonnementRepository $abonnementRepository, SerializerInterface $serializer): Response
    {
        $abonnements = $abonnementRepository->findAll();
        $data = $serializer->serialize($abonnements, 'json', ['groups' => 'Abn']);

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
    
    #[Route('/Abonnement/new', name: 'app_abonnement_new_Mobile')]
    public function new(Request $request, NormalizerInterface $Normalizer): Response
    {
                $em = $this->getDoctrine()->getManager();
                $Abonnement= new Abonnement();
                $Abonnement->setIdU($request->get('idU'));
                $Abonnement->setMoyenTransportA($request->get('moyenTransportA'));
                //$Abonnement->setDateA($request->get('dateA'));
                $dateAString = $request->get('dateA'); // assuming dateA is a string in 'Y-m-d' format
                $dateA = \DateTime::createFromFormat('Y-m-d', $dateAString);
                if ($dateA instanceof \DateTimeInterface) {
                    $Abonnement->setDateA($dateA);
                } else {
                    // handle invalid dateA input here, such as throwing an exception or returning an error message
                }

               // $Abonnement->setDateExpA($request->get('dateExpA'));
               $dateExpAString = $request->get('dateExpA'); // assuming dateExpA is a string in 'Y-m-d' format
               $dateExpA = \DateTime::createFromFormat('Y-m-d', $dateExpAString);
               if ($dateExpA instanceof \DateTimeInterface) {
                $Abonnement->setDateExpA($dateExpA);
            } else {
                // handle invalid dateA input here, such as throwing an exception or returning an error message
            }
                $Abonnement->setEtudiantA($request->get('etudiantA'));
                $Abonnement->setRedEtA($request->get('redEtA'));
                $em->persist($Abonnement);
                $em->flush();
        
                $jsonContent = $Normalizer->normalize($Abonnement, 'json', ['groups' => 'Abn']);
                return new Response("Abonnement ajoutée avec succées " . json_encode($jsonContent));

    }


//     #[Route('/Abonnements', name: 'list')]
//    // #[Groups(['Abn'])]
//     public function getAllAbonnements(AbonnementRepository $abonnementRepository, SerializerInterface $serializer)
// {
//     $abonnements = $abonnementRepository->findAll();
//     $data = $serializer->serialize($abonnements, 'json', ['groups' => 'Abn']);

//     return new Response($data, 200, ['Content-Type' => 'application/json']);
// }
}
