<?php
namespace App\Controller;


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



require_once __DIR__.'/../../vendor/autoload.php';

#[Route('/demande')]
class DemandeController extends AbstractController
{
    #[Route('/dd', name: 'app_demande', methods: ['GET'])]
    public function index(DemandeRepository $demandeRepository): Response
    {
       //dd($demandeRepository->findAll());
        return $this->render('demande/indexB.html.twig', [
            'demandes' => $demandeRepository->findAll(),
        ]);
    }


    #[Route('/{id}', name: 'app_demande_show', methods: ['GET'])]
    public function show(Demande $demande): Response
    {
        return $this->render('demande/showB.html.twig', [
            'demande' => $demande,
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
 
    #[Route(name: 'send-emailSMS-accept')]
public function sendEmailSMSAccept(MailerInterface $mailer, SessionInterface $session): Response
{
    $email = (new Email())
    ->from('nawres.lakhal@esprit.tn')
    ->to('nawres.lakhal2000@gmail.com')
    ->subject('Response To Request')
    ->text('Asked Accepted');

$mailer->send($email);

$accountSid = 'AC59fc270cf600cf72e19f55209c147e0e';
$authToken = 'bb1421e71da5ced6fc834953a6d043f2';
$twilioNumber = '+15676676188';

$clientA = new Client($accountSid, $authToken);

$clientA->messages->create(
    // Where to send a text message (your customer's phone number)
    '+21629117005',
    array(
        // The text message you want to send
        'from' => $twilioNumber,
        'body' => 'Asked Accepted'
    )
);
$this->addFlash('success', 'Email and SMS sent successfully.');
return $this->redirectToRoute('app_demande');

}
#[Route(name: 'send-emailSMS-refuse')]
public function sendEmailSMSRefuse(MailerInterface $mailer): Response
{
    $email = (new Email())
        ->from('nawres.lakhal@esprit.tn')
        ->to('nawres.lakhal2000@gmail.com')
        ->subject('Response To Request')
        ->text('Asked Refused');

    $mailer->send($email);

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
    $this->addFlash('success', 'Email and SMS sent successfully.');
    return $this->redirectToRoute('app_demande');
}

}
