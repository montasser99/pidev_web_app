<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Repository\AdresseRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\MailerService;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter; 

#[Route('/utilisateur')]
class UtilisateurController extends AbstractController
{ 
    #[Route('/', name: 'app_utilisateur_index', methods: ['GET'])]
    public function index(UtilisateurRepository $utilisateurRepository , Request $request): Response
    {
        //dd($this->getUser()->getRoleu());
        
        return $this->render('utilisateur/index.html.twig', [
            'utilisateurs' => $utilisateurRepository->findAll(),
        ]);
    
    }
 

    #[Route('/new', name: 'app_utilisateur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UtilisateurRepository $utilisateurRepository, SluggerInterface $slugger): Response
    {
        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           

            $imageFile = $form->get('imagepu')->getData();

            if ($imageFile) {
                // Set the image name as the current timestamp and the original file extension
                $imageName = time() . '.' . $imageFile->getClientOriginalExtension();

                // Move the file to the configured directory using VichUploader
                $imageFile->move(
                    $this->getParameter('uploads_directory'),
                    $imageName
                );

                // Update the item entity with the new image filename

                $utilisateur->setImagepu($imageName);
                if( $utilisateur->getRoles()=="Administrator")
                { $utilisateur->setRoleu('Admin');}
                else {
                    $utilisateur->setRoleu('Client');  
                }
            }
            $dateTime = new \DateTime();
            $formattedDate = $dateTime->format('Y-m-d\TH:i:s A');
            $utilisateur->setCreatedatu((string)$formattedDate);
        
            $utilisateurRepository->save($utilisateur, true);
            
            return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('utilisateur/new.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_utilisateur_show', methods: ['GET'])]
    public function show(Utilisateur $utilisateur): Response
    {
        return $this->render('utilisateur/show.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_utilisateur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Utilisateur $utilisateur, UtilisateurRepository $utilisateurRepository,AdresseRepository $adresseRepository,MailerService $mailer): Response
    {
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        // dd( $adresseRepository->find($request->request->all()["utilisateur"]["idadresse"]));

            $utilisateur->setIdadresse($adresseRepository->find($request->request->all()["utilisateur"]["idadresse"]));
            $utilisateurRepository->save($utilisateur);
            $message = " utilisateur a été mis à jour avec succès";
            $userEmail = $this->getUser()->getEmail();
            $mailer->sendEmail(to:$userEmail,content: $message);
            return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('utilisateur/edit.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_utilisateur_delete', methods: ['POST'])]
    public function delete(Request $request, Utilisateur $utilisateur, UtilisateurRepository $utilisateurRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$utilisateur->getId(), $request->request->get('_token'))) {
            $utilisateurRepository->remove($utilisateur, true);
        }

        return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
    }
    
   
}
