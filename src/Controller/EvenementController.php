<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Dompdf\Dompdf;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

#[Route('/evenement')]
class EvenementController extends AbstractController
{
//composer require dompdf/dompdf
    #[Route('/pdf', name: 'pdf', methods: ['GET'])]
    public function index_pdf(EvenementRepository $evenementRepository, Request $request){
        $now = new \DateTime();
        $dateNow = $now->format('Y-m-d H:i:s');
        $now = str_replace(':', '-', $dateNow);

// Récupération de la liste des événements à partir du repository
$evenements = $evenementRepository->findAll();
 
// Génération du HTML à partir du template Twig 'evenement/pdf_file.html.twig' en passant la liste des événements
$html = $this->renderView('evenement/pdf_file.html.twig', [
    'evenements' => $evenements,
]);
// Création d'une nouvelle instance de la classe Dompdf
$dompdf = new Dompdf();


// Récupération des options de Dompdf et activation du chargement des ressources à distance
//$options = $dompdf->getOptions();
//$options->set(array('isRemoteEnabled' => true));

// Application des options modifiées à Dompdf
//$dompdf->setOptions($options);

// Chargement du HTML généré dans Dompdf
$dompdf->loadHtml($html);

// Configuration du format de la page en A4 en mode portrait
//$dompdf->setPaper('A4', 'portrait');

// Génération du PDF
$dompdf->render();

// Récupération du contenu du PDF généré
$output = $dompdf->output();
// Create a temporary file to store the PDF
$tempFilename = tempnam(sys_get_temp_dir(), 'pdf_');
file_put_contents($tempFilename, $output);

// Create a response that points to the temporary PDF file
$response = new BinaryFileResponse(new File($tempFilename));
    
// Set the response headers to force a download and set the filename
$response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, "pfd_liste_events{$now}.pdf");


return $response;
}

// Affichage du PDF dans le navigateur avec le nom 'list.pdf' et en spécifiant que ce n'est pas une pièce jointe
//$dompdf->stream('list.pdf',["Attachement" => false]);

// Retourne la vue Twig 'evenement/index.html.twig' en passant la liste des événements
//return $this->render('evenement/index.html.twig', [
    //'evenements' => $evenementRepository->findAll(),
//]);
   // }
    
    #[Route('/calendrier', name: 'calendrier', methods: ['GET'])]
    public function calendrier(EvenementRepository $evenementRepository){
        // Récupération de tous les événements enregistrés dans la base de données
        $evenements = $evenementRepository->findAll();
    
        // Création d'un tableau  vide
        $rdvs = [];
    
        // Parcours de tous les événements pour les ajouter au tableau de evenement
        foreach ($evenements as $evenement) {
            // Création d'un tableau pour chaque evenement
            $rdv = [];
    
            // Ajout de l'identifiant de l'événement au tableau de evenement
            $rdv['id'] = $evenement->getIdEve();
    
            // Ajout du titre de l'événement au tableau de evenement
            $rdv['title'] = $evenement->getTitreEve();
    
            // Ajout de la description de l'événement au tableau de evenement
            $rdv['description'] = $evenement->getDescEve();
    
            // Ajout de la date de début de l'événement au tableau de evenement
            $rdv['start'] = $evenement->getDateDebEve()->format('Y-m-d');
    
            // Ajout de la date de fin de l'événement au tableau de evenement
            $rdv['end'] = $evenement->getDateFinEve()->format('Y-m-d');
    
            // Ajout de la couleur de fond de l'événement au tableau de evenement
            $rdv['backgroundColor'] = '#FF7474';
    
            // Ajout de la couleur de bordure de l'événement au tableau de evenement
            $rdv['borderColor'] = '#000000';
    
            // Ajout de la couleur de texte de l'événement au tableau de evenement
            $rdv['textColor'] = '#000000';
    
            // Désactivation de la modification de l'événement dans le calendrier
            $rdv['editable'] = false;
    
            // Ajout du tableau de rendez-vous de l'événement au tableau de rendez-vous global
            $rdvs[] = $rdv;
        }
    
        // Encodage du tableau de rendez-vous global au format JSON
        $data = json_encode($rdvs);
    
        // Affichage de la page du calendrier avec les données encodées
        return $this->render('evenement/calendrier.html.twig', compact('data'));
    }
    
    

    #[Route('/', name: 'app_evenement_index', methods: ['GET'])]
    public function index(EvenementRepository $evenementRepository): Response
    {
        // dd($evenementRepository->findAll());
        return $this->render('evenement/index.html.twig', [
            'evenements' => $evenementRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_evenement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EvenementRepository $evenementRepository,SluggerInterface $slugger): Response
    {
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            
            $imageFile = $form->get('image')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $imageFile->move(
                        $this->getParameter('image_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                    dd("waaaaa " + $e);
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $evenement->setImage($newFilename);
            }
            $evenementRepository->save($evenement, true);

            return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('evenement/new.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }

    #[Route('/{idEve}', name: 'app_evenement_show', methods: ['GET'])]
    public function show(Evenement $evenement): Response
    {
        return $this->render('evenement/show.html.twig', [
            'evenement' => $evenement,
        ]);
    }

    #[Route('/{idEve}/edit', name: 'app_evenement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Evenement $evenement, EvenementRepository $evenementRepository): Response
    {
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $evenementRepository->save($evenement, true);

            return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('evenement/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }

    #[Route('/{idEve}', name: 'app_evenement_delete', methods: ['POST'])]
    public function delete(Request $request, Evenement $evenement, EvenementRepository $evenementRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evenement->getIdEve(), $request->request->get('_token'))) {
            $evenementRepository->remove($evenement, true);
        }

        return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
    }
}
