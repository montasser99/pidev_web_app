<?php

namespace App\Controller\ControllerJson;

use App\Entity\Moyenstransport;
use App\Repository\MoyenstransportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use \Swift_Mailer ;


class MoyenstransportControllerJson extends AbstractController
{
    #[Route('/moyenstransportjson', name: 'app_moyenstransport_controller_json')]
    public function index(MoyenstransportRepository $moyenstransportRepository, SerializerInterface $serializer): Response
    {
        $moyens = $moyenstransportRepository->findAll();
        $json = $serializer->serialize($moyens, 'json', ['groups' => "moyens"]);

        return new Response($json);
    }

    /****************************************Ajouter une Moyenne de transport *********************************************/
    #[Route('/AjouterMoyensJson/new', name: 'app_moyenstransport_Ajouter')]
    public function ajouterCommentaireAction(Request $request, NormalizerInterface $Normalizer,\Swift_Mailer $mailer){

    $type=$request->query->get("type");
    $matricule=$request->query->get("matricule");
    $capacite=$request->query->get("capacite");
    $nummoy=$request->query->get("nummoy");

    $em = $this->getDoctrine()->getManager();

    $Moyenstransport = new Moyenstransport();
    $Moyenstransport->setType($type);
    $Moyenstransport->setMatricule($matricule);
    $Moyenstransport->setCapacite($capacite);
    $Moyenstransport->setNummoy($nummoy);

    $em->persist($Moyenstransport);
    $em->flush();

// Generate QR code
$qrcode = new QrCode($Moyenstransport->getMatricule());
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
    ->setTo("montabwi@gmail.com")
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




    $jsonContent = $Normalizer->normalize($Moyenstransport, 'json', ['groups' => 'moyens']);
    return new Response(json_encode($jsonContent));
}

//************************************** Modifier une moyenne de transport ************************************//
#[Route("ModifierMoyensJSON/{id}", name: "ModifierMoyensJSON")]
public function updateStudentJSON(Request $request,$id, NormalizerInterface $Normalizer)
{

    $em = $this->getDoctrine()->getManager();

    $type=$request->query->get("type");
    $matricule=$request->query->get("matricule");
    $capacite=$request->query->get("capacite");
    $nummoy=$request->query->get("nummoy");

    $moyenstransport = $em->getRepository(Moyenstransport::class)->find($id);
    $moyenstransport->setType($type);
    $moyenstransport->setMatricule($matricule);
    $moyenstransport->setCapacite($capacite);
    $moyenstransport->setNummoy($nummoy);

    $em->flush();

    $jsonContent = $Normalizer->normalize($moyenstransport, 'json', ['groups' => 'moyens']);
    return new Response("moyens de transport a ete modifier avec succées" . json_encode($jsonContent));
}

/***************************************** Supprimer une moyenne de transport  ****************************************/
#[Route("SupprimerMoyensJSON/{idmoy}", name: "supprimermoyensJSON")]
public function deleteStudentJSON(Request $req, $idmoy, NormalizerInterface $Normalizer)
{

    $em = $this->getDoctrine()->getManager();
    $moyens = $em->getRepository(Moyenstransport::class)->find($idmoy);
    $em->remove($moyens);
    $em->flush();
    $jsonContent = $Normalizer->normalize($moyens, 'json', ['groups' => 'moyens']);
    return new Response("moyens de transport a ete supprimer avec succées " . json_encode($jsonContent));
}


}
