<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\RegistrationFormType;
use App\Security\AppAuthentificatorAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Filesystem\Filesystem;
use App\Service\MailerService;


class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, AppAuthentificatorAuthenticator $authenticator, EntityManagerInterface $entityManager,MailerService $mailer): Response
    {
        $user = new Utilisateur();
        $form = $this->createForm(RegistrationFormType::class, $user);
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

                $user->setImagepu($imageName);
                $user->setRoleu('Client');
                $dateTime = new \DateTime();
                $formattedDate = $dateTime->format('Y-m-d\TH:i:s A');
                $user->setCreatedatu((string)$formattedDate);
            }
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
       
            $message = "Welcome " . $user->getNomu() . " " . $user->getNomu() . "  We are excited to welcome you as a new member of our community! Your account has been successfully created and you can now log in to our website using your email address and the password you chose during registration.\n\n" .
            "If you have any questions, suggestions, or feedback, don't hesitate to contact us .\n\n" .
        "Thank you for joining us, and we hope you enjoy using our website!\n\n" ;

            $userEmail = $user->getEmail();
            $mailer->sendEmail(to:$userEmail,content: $message);
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
    }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
