<?php

namespace App\Controller\ControllerJson;

use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

// #[Route('/reclamationjson')]
class  ReclamationControllerjson extends AbstractController
{
    #[Route('reclamationjson/Recjson', name: 'app_reclamation_show_json')]
    public function index(ReclamationRepository $reclamationRepository, SerializerInterface $serializer): Response
    {
        $reclamation = $reclamationRepository -> findAll();  
        $json = $serializer->serialize($reclamation, 'json', ['groups' => 'reclams']);
        return new Response($json);
    }
    #[Route('reclamationjson/newjson', name: 'app_reclamation_new_json')]
    public function new(Request $request, NormalizerInterface $Normalizer ): Response
    {
    $nom = $request -> query->get("nom");
    $prenom = $request -> query->get("prenom");
    $now = new \DateTime();
    $dateNow = $now->format('Y-m-d ');
    $dateObj = DateTime::createFromFormat('Y-m-d ', $dateNow);

    $descrec = $request -> query->get("desc");   
    $em = $this->getDoctrine()->getManager();
    $reclamation = new reclamation();
    $reclamation->setNom($nom);
    $reclamation->setPrenom($prenom);
    $reclamation->setDescrec($descrec);
    $reclamation->setDater($dateObj);
    $em->persist($reclamation);
    $em->flush();

    $jsonContent = $Normalizer->normalize($reclamation, 'json', ['groups' => 'reclams']);
    return new Response("reclamation a éte ajouter avec succées" . json_encode($jsonContent));
    }  
    #[Route("reclamationjson/editjson/{id}", name: "app_reclamation_edit_json")]
public function editreclamJSON(Request $request,$id, NormalizerInterface $Normalizer)
{

    $em = $this->getDoctrine()->getManager();

    $nom = $request -> query->get("nom");
    $prenom = $request -> query->get("prenom");
    $now = new \DateTime();
    $dateNow = $now->format('Y-m-d ');
    $dateObj = DateTime::createFromFormat('Y-m-d ', $dateNow);
    $descrec = $request -> query->get("desc");

    $reclamation = $em->getRepository(reclamation::class)->find($id);
    $reclamation->setNom($nom);
    $reclamation->setPrenom($prenom);
    $reclamation->setDescrec($descrec);
    $reclamation->setDater($dateObj);

    $em->flush();

    $jsonContent = $Normalizer->normalize($reclamation, 'json', ['groups' => 'reclams']);
    return new Response("reclamation a éte modifier avec succées" . json_encode($jsonContent));
}
#[Route("reclamationjson/removejson/{id}", name: "app_reclamation_remove_json")]
public function deleteReclamJSON($id, NormalizerInterface $Normalizer)
{

    $em = $this->getDoctrine()->getManager();
    $reclamation = $em->getRepository(reclamation::class)->find($id);    $em->remove($reclamation);
    $em->flush();
    $jsonContent = $Normalizer->normalize($reclamation, 'json', ['groups' => 'reclams']);
    return new Response("Reclamation a éte supprimer avec succées " . json_encode($jsonContent));
}
    
}