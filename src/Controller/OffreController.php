<?php

namespace App\Controller;

use App\Entity\Offre;
use App\Form\OffreType;
use App\Repository\OffreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Swal;
use App\Entity\Participation;
use App\Repository\ParticipationRespository;
use App\Repository\EvenementRepository;
use App\Entity\Evenement;
use App\Entity\Alerte;
use App\Form\AlerteType;
use App\Repository\AlerteRepository;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/offre')]
class OffreController extends AbstractController
{

   
    #[Route('/', name: 'app_offre_index', methods: ['GET'])]
    public function index(OffreRepository $offreRepository): Response
    {
        return $this->render('offre/index.html.twig', [
            'offres' => $offreRepository->findAll(),
        ]);
    }
    #[Route('/Front', name: 'app_offre_front', methods: ['GET'])]
    //pagination
    public function showEvents(Request $request,PaginatorInterface $paginator,AlerteRepository $alerteRepository,ParticipationRespository $a ,OffreRepository $offreRepository): Response
    {
        // Récupération de toutes les alertes enregistrées dans la base de données
        $alerts = $alerteRepository->findAll();
    
        // Récupération de toutes les offres enregistrées dans la base de données
        $query =$offreRepository->findAll();
        
        // Utilisation du service "paginator" de Symfony pour paginer les résultats de la requête
        $offre = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            3 // nombre d'éléments par page
        );
    
        // Initialisation de la variable "Logged" à "false"
        $Logged = false;
    
        // Vérification si un utilisateur est connecté ou non
        if(!$Logged){
            // Récupération des participations de l'utilisateur connecté pour l'événement ayant l'ID 2
            $check=$a->selectbyevent(2);
    
            // Rendu de la vue "showEvents.html.twig" avec les résultats paginés des offres, les résultats de vérification de participation
            return $this->render('offre/showEvents.html.twig', [
                'offres' =>$offre,'check' => $check,'alerts' => $alerts
            ]);
        }else{
            // Rendu de la vue "showEvents.html.twig" avec les résultats paginés des offres 
            return $this->render('offre/showEvents.html.twig',['offres'=>$offre,'alerts' => $alerts]);
        }
    }


    #[Route('/searchevenement', name: 'searchevenement', methods: ['GET', 'POST'])]
    public function searchevenement(Request $request, OffreRepository $offreRepository)
    {
        // Récupérer l'instance de EntityManager de Doctrine
        $em = $this->getDoctrine()->getManager();
        
        // Récupérer le paramètre de requête "q" envoyé via GET ou POST
        $requestString = $request->get('q');
        
        // Appeler la méthode findEntitiesByString de OffreRepository pour trouver les offres correspondant à la requête
        $offres =  $offreRepository->findEntitiesByString($requestString);
        
        // Vérifier si aucune offre n'a été trouvée
        if(!$offres) {
            $result['offres']['error'] = "Pas de evenements ! 🙁 ";
        } else {
            // Appeler la méthode getRealEntities pour formater les offres trouvées en un tableau compréhensible pour l'utilisateur
            $result['offres'] = $this->getRealEntities($offres);
        }
        
        // Renvoyer une réponse JSON contenant les résultats de la recherche
        return new Response(json_encode($result));
    }
    
    
    public function getRealEntities($offres)
    { 
        // recherche 
        // Boucler sur les offres trouvées pour créer un tableau formaté compréhensible pour l'utilisateur
        foreach ($offres as $offre) {
            $realEntities[$offre->getIdOffreEve()] = [
                $offre->getIdOffreEve(),
                $offre->getIdu()->getImage(),
                $offre->getIdu()->getTitreEve(),
                $offre->getIdu()->getDescEve(),
                $offre->getIdu()->getDateDebEve()->format('Y-m-d'),
                $offre->getIdu()->getDateFinEve()->format('Y-m-d'),
                $offre->getIdu()->getPrix(),
                $offre->getBudgetOffre()
            ];
        }
        
        // Renvoyer le tableau formaté
        return $realEntities;
    }
    

   
    

    #[Route('/new', name: 'app_offre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, OffreRepository $offreRepository): Response
    {
        $offre = new Offre();
        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $offreRepository->save($offre, true);

            
            

            return $this->redirectToRoute('app_offre_index', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->renderForm('offre/new.html.twig', [
            'offre' => $offre,
            'form' => $form,
        ]);
    }

    #[Route('/{idOffreEve}', name: 'app_offre_show', methods: ['GET'])]
    public function show(Offre $offre): Response
    {
        return $this->render('offre/show.html.twig', [
            'offre' => $offre,
        ]);
    }

    #[Route('/{idOffreEve}/edit', name: 'app_offre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Offre $offre, OffreRepository $offreRepository): Response
    {
        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $offreRepository->save($offre, true);

            return $this->redirectToRoute('app_offre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offre/edit.html.twig', [
            'offre' => $offre,
            'form' => $form,
        ]);
    }

    #[Route('/{idOffreEve}', name: 'app_offre_delete', methods: ['POST'])]
    public function delete(Request $request, Offre $offre, OffreRepository $offreRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$offre->getIdOffreEve(), $request->request->get('_token'))) {
            $offreRepository->remove($offre, true);
        }

        return $this->redirectToRoute('app_offre_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/participer/{id}', name: 'participer')]
public function participer(MailerInterface $mailer,$id,Request $request,EvenementRepository $a): Response
{          
    
    //find event by his id
    $rep=$this->getDoctrine()->getRepository(Evenement::class);
    $event=$rep->find($id);
    //find user by his id
  

    $participation = new Participation();
    $participation->setEvenement($event);
          
    //$this->getUser()->getId()
      $participation->setUser($this->getUser()->getId());
      $em=$this->getDoctrine()->getManager();
      $email = (new Email())
      ->from(new Address("salsabil.hamraoui@esprit.tn"))                
      ->to(new Address($this->getUser()->getEmail()))
      ->subject("News")
      ->html('
          <center><h1>Hello salsabil,</h1></center>
          <p>Vous avez participer a l event '.$event->getTitreEve().' </p>
      ');

  $mailer->send($email);
/*Le code ci-dessus envoie un email à l'adresse "salsabil.hamraoui@esprit.tn" 
en utilisant l'adresse email "salsabil.hamraoui@esprit.tn" comme expéditeur. 
Le sujet de l'email est "News" et le contenu est un message HTML qui inclut 
le titre de l'événement récupéré à partir de l'objet "event" grâce à la méthode "getTitreEve()".
Ensuite, la méthode "send" est appelée sur l'objet "mailer" pour envoyer l'email. 
Cette méthode envoie l'email en utilisant les paramètres du serveur SMTP configurés dans l'objet Mailer.*/
      $em->persist($participation);
      $em->flush();

    

    return $this->redirectToRoute('app_offre_front');
    


  
} 
#[Route('/imparticiper/{id}', name: 'imparticiper')]
public function imparticiper(MailerInterface $mailer,$id,ParticipationRespository $b): Response
{    
    //find event by his id
    $rep=$this->getDoctrine()->getRepository(Evenement::class);
     $event=$rep->find($id);
    //$this->getUser()->getId()
    
    $b->delete($event,$this->getUser()->getId());
    $email = (new Email())
    ->from(new Address("salsabil.hamraoui@esprit.tn"))                
    ->to(new Address($this->getUser()->getEmail()))
    ->subject("News")
    ->html('
        <center><h1>Hello salsabil,</h1></center>
        <p>Vous avez imparticiper a l event '.$event->getTitreEve().' </p>
    ');

$mailer->send($email);
/*Le code ci-dessus envoie un email à l'adresse "salsabil.hamraoui@esprit.tn" 
en utilisant l'adresse email "salsabil.hamraoui@esprit.tn" comme expéditeur. 
Le sujet de l'email est "News" et le contenu est un message HTML qui inclut 
le titre de l'événement récupéré à partir de l'objet "event" grâce à la méthode "getTitreEve()".
Ensuite, la méthode "send" est appelée sur l'objet "mailer" pour envoyer l'email. 
Cette méthode envoie l'email en utilisant les paramètres du serveur SMTP configurés dans l'objet Mailer.*/
    return $this->redirectToRoute('app_offre_front');
}
}
