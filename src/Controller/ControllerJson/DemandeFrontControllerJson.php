<?php

namespace App\Controller\ControllerJson;


use App\Entity\Demande;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class DemandeFrontControllerJson extends AbstractController

    {
       

    #[Route("/addDemandeJSON", name: "addDemandeJSON")]
    public function newDemande(Request $request, NormalizerInterface $Normalizer ): Response
    {
        $em = $this->getDoctrine()->getManager();
        $demande = new Demande();
        $demande->setNomc($request->get('nomc'));
        $demande->setMoyen($request->get('moyen'));
        $demande->setDated($request->get('dated'));
        $demande->setDatea($request->get('datea'));
        $demande->setPermis($request->get('permis'));
        $demande->setEmailc($request->get('email'));
        $em->persist($demande);
        $em->flush();

        $jsonContent = $Normalizer->normalize($demande, 'json', ['groups' => 'demandes']);
        return new Response("Demande added successfully " . json_encode($jsonContent));
   
          
        }

      
    
    }