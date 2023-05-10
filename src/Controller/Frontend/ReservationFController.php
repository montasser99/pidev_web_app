<?php

namespace App\Controller\Frontend;


use App\Entity\Reservation;
use App\Repository\CircuitRepository;
use App\Repository\MoyenstransportRepository;
use App\Repository\PlanningRepository;
use App\Repository\ReservationRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use \Swift_Mailer ;
use App\Controller\SmsController;
use Twilio\Rest\Client;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Swift_Attachment;

class ReservationFController extends AbstractController
{


    #[Route('/reservation/f', name: 'app_reservation_f')]
    public function index(ReservationRepository $reservationRepository): Response
    {
        return $this->render('frontend/reservation_f/index.html.twig', [
            'reservations' => $reservationRepository->findAll(),
        ]);
    }

    #[Route('/reservation/RechercheResv',name:'app_reservation_Rindex')]
    public function Reservation(PlanningRepository $planning){
        //recuperer circuit a partir de planning
        $circuits=$planning->getDistinctCircuits();
        return $this->render('frontend/reservation_f/Reservation1.html.twig',[
            'circuits'=>$circuits,
        ]);
    }

    #[Route('/reservation/Rech/',name:'app_Reservation_RV')]
    public function ReservationRV(Request $request, PlanningRepository $planningRepository, CircuitRepository $circuitRepository, MoyenstransportRepository $moyenstransportRepository)
    {
        // Get the circuit and transportName parameters from the request
        $circuit = $request->request->get('circuit');        
        $transportName = $request->request->get('transportName');

        //copy of code ciruit depart et cirucit Arriveé
        if($circuit == "" || $transportName == null ){
            $this->addFlash("Message","Les champs sont obligatoire !");
            return $this->redirectToRoute('app_reservation_Rindex');
        }    
        if(strpos($circuit, " - ") == false ){
            $this->addFlash("Message","Le Format de circuit non valide !");
            return $this->redirectToRoute('app_reservation_Rindex');
        }
        $cities = explode(" - ", $circuit);
        $cirD = $cities[0]; 
        $cirA = $cities[1];
        //recuperer date now
        $now = new \DateTime();
        $dateNow = $now->format('H:i:s'); 

        // dd($circuit,$transportName);

        //recuperer l'id de circuit        
        //recuperer l'id de moyenne de transport
        $idCircuit = $circuitRepository->getIdCircuit($cirD,$cirA);
        $idTransport=$moyenstransportRepository->getIdMoy($transportName);

        if(sizeof($idCircuit) == 0)
        {
            $this->addFlash("Message","Ce circuit n'est pas existe !");
            return $this->redirectToRoute('app_reservation_Rindex');
        } 
        $idC=$idCircuit[0]["idcircuit"];
        $idM=$idTransport[0]["idmoy"];

        //requette de recherche
        $Result=$planningRepository->RechercheReservPlanning($idC, $idM, $dateNow);
        //on peut tester a partir de ce dd :
        // dd($Result,$idM,$idC);

        return $this->render('frontend/reservation_f/Reservation2.html.twig',[
            'Results'=>$Result,
        ]);
    }

    #[Route('/reservation/ReserverPlace/{dated}/{idcir}/{idmoy}',name:'app_reserver_place')]
    public function ReservationPlace(Request $request, PlanningRepository $planning ,$dated,$idcir,$idmoy, ReservationRepository $ReservationRepository , \Swift_Mailer $mailer , Client $twilio , SmsController $smsController){
    
// dd($dated , $idcir,$idmoy);
    $PlanningR = $planning->getPlanning($dated,$idcir,$idmoy);
    // dd($PlanningR); 
    //pour le moment en passe une cin random de length 8 de type integer and after we change with session ==> str_pad(random_int(0, 99999999), 8, '0', STR_PAD_LEFT)
    // dd($PlanningR[0]->getDated());
    $now = new \DateTime();
    $dateNow = $now->format('Y-m-d H:i:s');
    $dateObj = DateTime::createFromFormat('Y-m-d H:i:s', $dateNow);
    $dateobj1= DateTime::createFromFormat('H:i:s',$dateNow);
    // dd($dateobj1);


    //organiser les attributs de table reservation
    $dateR=$now->format('H:i:s');
    $heureDep=$PlanningR[0]->getDated();
    $heureDepart= DateTime::createFromFormat('H:i:s',$heureDep);
    $heureArr=$PlanningR[0]->getDatea();
    $heureArrive= DateTime::createFromFormat('H:i:s',$heureArr);
    $type=$PlanningR[0]->getIdmoy()->getType();
    //pour le moment en passe une cin random de length 8 de type integer and after we change with session 
    $cin = '13025486';
    $prix = $PlanningR[0]->getPrix();
    $NumeroT=$PlanningR[0]->getIdmoy()->getNummoy();
    $DateReserv=$dateNow = $now->format('Y-m-d H:i:s');

    //creer une new instance of reservation and add data
    $reservation =  new Reservation();
    $reservation->setDater($dateObj);
    $reservation->setHeuredep($heureDepart);
    $reservation->setHeurearr($heureArrive);
    $reservation->setType($type);
    $reservation->setCin($cin);
    $reservation->setPrix($prix);
    $reservation->setNumerot($NumeroT);
    $reservation->setDateReservation($dateObj);



    $ReservationRepository->save($reservation, true);    
    
// Generate QR code
$qrcode = new QrCode($reservation->getIdnum());
$qrcodeWriter = new PngWriter();
$qrcodeResult = $qrcodeWriter->write($qrcode);
$qrcodeDataUri = $qrcodeResult->getDataUri();

// Create GD image from QR code
$qrImage = imagecreatefromstring(file_get_contents($qrcodeDataUri));

// Create base image
$image = imagecreatetruecolor(400, 400);
$backgroundColor = imagecolorallocate($image, 255, 255, 255);
imagefill($image, 0, 0, $backgroundColor);

// Add QR code to base image
imagecopy($image, $qrImage, 50, 50, 0, 0, imagesx($qrImage), imagesy($qrImage));

// Output image
ob_start();
imagepng($image);
$qrcodeImage = ob_get_clean();

// Attach QR code image to email message
$message = (new \Swift_Message('Reservation avec succées'))
    ->setFrom('montasser.benouirane@esprit.tn')
    ->setTo($this->getUser()->getEmail())
    ->setBody(
        $this->renderView(
            'frontend/reservation_f/MessageReservation.html.twig',
            ['qrcodeDataUri' => $qrcodeDataUri]
        ),
        'text/html'
    )
    ->attach(
        (new \Swift_Attachment($qrcodeImage, 'qrcode.png', 'image/png'))
            ->setDisposition('inline')
    );

$mailer->send($message);

// Destroy images
imagedestroy($image);
imagedestroy($qrImage);

        //sending SMS
        $ticket = 'winek'; // Replace with your actual ticket value
        //after Session get true number here 
        $phoneNumber = '+21626923145'; // Replace with the recipient's phone number

        // Send the SMS
        //$smsController->sendSms($twilio, $phoneNumber, $ticket);




    
    //diminuer 1 sur reservation 
    $planning->AddOneToReservation($dated,$idcir,$idmoy);




    //recuperer liste 
        // ==> give me code here 
        $this->addFlash('notice','Submitted Successfully !');
    // Redirect to a new page
    return $this->redirectToRoute('app_reservation_success');
}

#[Route('/reservation/success', name: 'app_reservation_success')]
public function reservationSuccess(ReservationRepository $reservationRepository)
{

//getReservation where dateReservation a égale a date de maintenant  session cin maintenant en passe statique  

//date de ce jours 
$now = new \DateTime();
$dateObj = $now->format('Y-m-d');

// dd($dateObj);
//cin statique (apres avec session)
$cin=13025486;
$Reserv=$reservationRepository->getReservationDejour($cin,$dateObj);

    return $this->render('frontend/reservation_f/Reservation3.html.twig',[
        'Reserv'=>$Reserv,
    ]);
}
#[Route('/reservation/Historique', name: 'app_reservation_historique')]
public function reservationHistorique(ReservationRepository $reservationRepository)
{
//cin statique (apres avec session)
$cin=13025486;
$Reserv=$reservationRepository->getReservationHistorique($cin);
$message=true;

    return $this->render('frontend/reservation_f/Reservation3.html.twig',[
        'Reserv'=>$Reserv,
        "message"=>$message,
    ]);
}


}
