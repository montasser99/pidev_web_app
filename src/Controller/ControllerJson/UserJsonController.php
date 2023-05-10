<?php
namespace App\Controller\ControllerJson;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use App\Entity\Utilisateur;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\UtilisateurRepository;
use App\Service\MailerService;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;


class UserJsonController extends AbstractController
{
    #[Route('/user/json', name: 'app_user_json')]
    public function index(): Response
    {
        return $this->render('user_json/index.html.twig', [
            'controller_name' => 'UserJsonController',
        ]);
    }
    #[Route('/display', name: 'display' ,  methods: ['GET', 'POST'])]
    public function allusers(Request $request,UtilisateurRepository $userRepository){
    $user = $this->getDoctrine()->getManager()->getRepository(Utilisateur::class)->findAll();
    $serializer = new Serializer([new ObjectNormalizer()]);
    $formatted = $serializer->normalize($user);
    return new JsonResponse($formatted);
}

    #[Route('/ajouteruser', name: 'ajout' ,  methods: ['GET', 'POST'])]
    public function ajouteruser(Request $request,UtilisateurRepository $userRepository) {
        $user = new Utilisateur();
        $email = $request->query->get("email");
        $password = $request->query->get("password");
        $lastname= $request->query->get("nom");
        $name = $request->query->get("prenom");
        $profilepicture= $request->query->get("image");
        $role= $request->query->get("role");
        $phone= $request->query->get("phone");
        $cin= $request->query->get("cin");
        $entityManager= $this->getDoctrine()->getManager();
      
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setNomu($lastname);
        $user->setPrenomu($name);
        $user->setImagepu($profilepicture);
        $user->setRoleu($role);
        $user->setTelephoneu($phone);
        $user->setCinu($cin);
        $entityManager->persist($user);
        $entityManager->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($user);
      
        return new JsonResponse($formatted);
      
      }
      #[Route("updateUserJSON/{id}", name: "updateUserJSON")]
      public function updateStudentJSON(Request $req, $id, NormalizerInterface $Normalizer)
      {
  
          $em = $this->getDoctrine()->getManager();
          $user = $em->getRepository(Utilisateur::class)->find($id);
          $user->setEmail($req->get('email'));
          $user->setNomu($req->get('name'));
  
          $em->flush();
  
          $jsonContent = $Normalizer->normalize($user, 'json', ['groups' => 'User']);
          return new Response("User updated successfully " . json_encode($jsonContent));
      }
      #[Route("deleteUserJSON/{id}", name: "deleteStudentJSON")]
    public function deleteStudentJSON(Request $req, $id, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $user= $em->getRepository(Utilisateur::class)->find($id);
        $em->remove($user);
        $em->flush();
        $jsonContent = $Normalizer->normalize($user ,'json', ['groups' => 'User']);
        return new Response("User deleted successfully " . json_encode($jsonContent));
    }

#[Route('/user/signup', name: 'app_register_json' ,  methods: ['GET', 'POST'])]
    public function signupAction(Request $request,UserPasswordHasherInterface $userPasswordHasher,MailerService $mailer) {
        $user = new Utilisateur();
        $email = $request->query->get("email");
        $password = $request->query->get("password");
        $lastname= $request->query->get("nom");
        $name = $request->query->get("prenom");
        $profilepicture= $request->query->get("image");
        $roles= $request->query->get("roles");
        $role= $request->query->get("role");
        if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
             return new Response("email not valid");
        }
        
        $entityManager= $this->getDoctrine()->getManager();
      
        $user->setEmail($email);
        $user->setPassword(
            $userPasswordHasher->hashPassword(
                $user,
                $password
            )
        );
        $user->setNomu($lastname);
        $user->setPrenomu($name);
        $user->setImagepu($profilepicture);
        $user->setRoles(array($roles));
        $user->setRoleu($role);
        

        $entityManager->persist($user);
        $entityManager->flush();

        
        $message = "Welcome " . $user->getNomu() . " " . $user->getNomu() . "  We are excited to welcome you as a new member of our community! Your account has been successfully created and you can now log in to our website using your email address and the password you chose during registration.\n\n" .
        "If you have any questions, suggestions, or feedback, don't hesitate to contact us .\n\n" .
    "Thank you for joining us, and we hope you enjoy using our website!\n\n" ;

        $userEmail = $user->getEmail();
        $mailer->sendEmail(to:$userEmail,content: $message);
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($user);
      
        return new JsonResponse("Account is created",200);
      
      }      

      #[Route('/user/signin', name: 'app_login_json' ,  methods: ['GET', 'POST'])]
      public function signinAction(Request $request,UserPasswordHasherInterface $userPasswordHasher)
      { $email = $request->query->get("email");
        $password = $request->query->get("password");
        $entityManager= $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(Utilisateur::class)->findOneBy(['email'=>$email]);
        if ($user) {
            if ( password_verify($password, $user->getPassword())) {
                $serializer = new Serializer([new ObjectNormalizer()]);
                $formatted = $serializer->normalize($user);
                return new JsonResponse($formatted);
         
            }
            else {
                return new Response("password not found");
            }
        }
        else {
            return new Response("user not found");
        } 
    }
    #[Route('/user/editUser', name: 'app_gestion_profile' ,  methods: ['GET', 'POST'])]
    public function editUser(Request $request,UserPasswordHasherInterface $userPasswordHasher)
    {   //$user=new Utilisateur;
        $id = $request->get("id");
        $email = $request->query->get("email");
        $password = $request->query->get("password");
        $lastname= $request->query->get("nom");
        $name = $request->query->get("prenom");
        $entityManager= $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(Utilisateur::class)->find($id);

        $user->setPassword(
            $userPasswordHasher->hashPassword(
                $user,
                $password
            )
        );
        $user->setEmail($email);
        $user->setNomu($lastname);
        $user->setPrenomu($name);
       try{
            $entityManager= $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            return new JsonResponse("success",200);

       }catch (\Exception $ex){
        return new Response("Failed".$ex->getMessage());
       }

    }


}