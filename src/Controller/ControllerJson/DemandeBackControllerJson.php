<?php
namespace App\Controller\ControllerJson;


use App\Entity\Demande;
use App\Form\DemandeType;
use App\Repository\DemandeRepository;
use App\Repository\PlanningRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Twilio\Rest\Client;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;





class DemandeBackControllerJson extends AbstractController
{
    #[Route("/demandesJSON", name: "liste")]
    public function getDemandes(DemandeRepository $demandeRepository , SerializerInterface $serializer): Response
    {
        $demandes = $demandeRepository->findAll();
        //    dd($demandes);
        $json = $serializer->serialize($demandes, 'json', ['groups' => "demandes"]);

        return new Response($json);

   
}

#[Route("/SMSjsonAccept", name: "sms_ok")]
    public function acceptSMSAccept(DemandeRepository $demandeRepository , SerializerInterface $serializer): Response
    {
        $accountSid = 'AC59fc270cf600cf72e19f55209c147e0e';
        $authToken = 'bb1421e71da5ced6fc834953a6d043f2';
        $twilioNumber = '+15676676188';
        
        $clientR = new Client($accountSid, $authToken);
    
        $clientR->messages->create(
            // Where to send a text message (your customer's phone number)
            '+21629117005',
            array(
                // The text message you want to send
                'from' => $twilioNumber,
                'body' => 'Asked Accepted'
            )
        );

        return new Response("SMS sent successfully ");
    }
    #[Route("/SMSjsonRefuse", name: "sms_ok2")]
    public function acceptSMSRefuse(DemandeRepository $demandeRepository , SerializerInterface $serializer): Response
    {
        $accountSid = 'AC59fc270cf600cf72e19f55209c147e0e';
        $authToken = 'bb1421e71da5ced6fc834953a6d043f2';
        $twilioNumber = '+15676676188';
        
        $clientR = new Client($accountSid, $authToken);
    
        $clientR->messages->create(
            // Where to send a text message (your customer's phone number)
            '+21629117005',
            array(
                // The text message you want to send
                'from' => $twilioNumber,
                'body' => 'Asked Refused'
            )
        );

        return new Response("SMS sent successfully ");
    }   

       

   
}



