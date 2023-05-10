<?php

namespace App\Controller;

use App\Entity\Repreclamation;
use App\Entity\Utilisateur;
use App\Form\RepreclamationType;
use App\Repository\RepreclamationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use \Swift_Mailer ;

#[Route('/repreclamation')]
class RepreclamationController extends AbstractController
{
    // private $mailer;

    // public function __construct(MailerInterface $mailer)
    // {
    //     $this->mailer = $mailer;
    // }

    #[Route('/', name: 'app_repreclamation_index', methods: ['GET'])]
    public function index(RepreclamationRepository $repreclamationRepository): Response
    {
        return $this->render('repreclamation/index.html.twig', [
            'repreclamations' => $repreclamationRepository->findAll(),
        ]);
    }


    #[Route('/new', name: 'app_repreclamation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, RepreclamationRepository $repreclamationRepository , \Swift_Mailer $mailer): Response
    // , MailerInterface $mailer in the arguments for new ( ) 
        {
        // Create a new instance of Swift_SmtpTransport with the SMTP server settings
    $transport = new \Swift_SmtpTransport('smtp.gmail.com', 587, 'tls');
    $transport->setUsername('firas.saafi@esprit.tn');
    $transport->setPassword('lrhujexzfiwaqmlu');
    
    // Create a new instance of Swift_Mailer with the transport instance
    $mailer = new \Swift_Mailer($transport);
        
        $repreclamation = new Repreclamation();
        $form = $this->createForm(RepreclamationType::class, $repreclamation);
        $form->handleRequest($request);
        $idr = $request->get("idr");
        // $usr = new Utilisateur();


        if ($form->isSubmitted() && $form->isValid()) {
            $repreclamation->setIdr($idr);
            // $repreclamation->setIdu($usr);
            $repreclamationRepository->save($repreclamation, true);
            
            $message = (new \Swift_Message('Vous avez une nouvelle réponse de l admin TakTak'))
            ->setFrom('firas.saafi@esprit.tn')
            ->setTo($this->getUser()->getEmail())
            ->setBody(
                $this->renderView(
                    'NotificationReponse.html.twig',
                ),
                'text/html'
            );
        $mailer->send($message);


            return $this->redirectToRoute('app_repreclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('repreclamation/new.html.twig', [
            'repreclamation' => $repreclamation,
            'form' => $form,
        ]);
    }


    #[Route('/{idrep}', name: 'app_repreclamation_show', methods: ['GET'])]
    public function show(Repreclamation $repreclamation): Response
    {
        return $this->render('repreclamation/show.html.twig', [
            'repreclamation' => $repreclamation,
        ]);
    }

    #[Route('/{idrep}/edit', name: 'app_repreclamation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Repreclamation $repreclamation, RepreclamationRepository $repreclamationRepository): Response
    {
        $form = $this->createForm(RepreclamationType::class, $repreclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repreclamationRepository->save($repreclamation, true);

            return $this->redirectToRoute('app_repreclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('repreclamation/edit.html.twig', [
            'repreclamation' => $repreclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{idrep}', name: 'app_repreclamation_delete', methods: ['POST'])]
    public function delete(Request $request, Repreclamation $repreclamation, RepreclamationRepository $repreclamationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $repreclamation->getIdrep(), $request->request->get('_token'))) {
            $repreclamationRepository->remove($repreclamation, true);
        }

        return $this->redirectToRoute('app_repreclamation_index', [], Response::HTTP_SEE_OTHER);
    }
}
