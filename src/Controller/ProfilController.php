<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'profil')]
    public function index(): Response
    {
        return $this->render('Profil.html.twig', [
            'controller_name' => 'ProfilController',
        ]);
    }
    
    #[Route('/edit_profil', name: 'app_update_profil', methods: ['POST'])]
    public function updateProfile(Request $request, UtilisateurRepository $userRepository,EntityManagerInterface $entityManager)
    {
        // Get the current user ID
       
        $userId = $this->getUser()->getId();
    
        // Find the user record in the database
        //$user = $userRepository->find($userId);
        
        $user = $entityManager->getRepository(Utilisateur::class)->find($userId);
        $user->setNomu($request->request->get('name'));
        $user->setPrenomu($request->request->get('surname'));
        $user->setCinu($request->request->get('cin'));
        $user->setTelephoneu($request->request->get('phone'));
        //$user->setIdAdresse($request->request->get('adresse'));
        // TODO: Update the user record with the new values from the form
    
        // Redirect the user back to the profile page
        // $user->setNomu('name');
        $entityManager->flush();


        

        return $this->redirectToRoute('profil');
    }
  

// ...

#[Route('/change_password', name: 'app_change_password', methods: ['POST'])]
public function changePassword(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
{
    $user = $this->getUser();

    // Check if the current password is correct
    if (!$passwordEncoder->isPasswordValid($user, $request->request->get('password'))) {
        // Return an error message or redirect to a page with an error message
    }

    // Check if the new password and re-entered password match
    $newPassword = $request->request->get('newpassword');
    $renewPassword = $request->request->get('renewpassword');
    if ($newPassword !== $renewPassword) {
        // Return an error message or redirect to a page with an error message
    }

    // Encode and set the new password
    $encodedPassword = $passwordEncoder->encodePassword($user, $newPassword);
    $user->setPassword($encodedPassword);
    $this->getDoctrine()->getManager()->flush();

    // Redirect the user to the profile page with a success message
    return $this->redirectToRoute('profil', ['successMessage' => 'Your password has been changed successfully.']);
}
    
    
}
