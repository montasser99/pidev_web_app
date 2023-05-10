<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use App\Entity\Service\DompdfService;
use Dompdf\Dompdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;


class EtatjaurnaliereController extends AbstractController
{
    #[Route('/etatjaurnaliere', name: 'app_etatjaurnaliere')]
    public function index(ReservationRepository $ReservRep): Response
    {
        $now = new \DateTime();
        $dateNow=$now->format('Y-m-d');
        $Result = $ReservRep->GetReservation($dateNow);
        // dd($Result);

        return $this->render('etatjaurnaliere/index.html.twig', [
            'etatJ' => $Result,
        ]);
    }

    #[Route('/signature/{id}-{type}-{numeroT}-{total}-{dateRes}', name: 'app_signature', methods: ['GET'])]
    public function Signature($id,$type,$numeroT,$total,$dateRes){

        return $this->render("/download_signature/index.html.twig", [
            'id' => $id,
            'type' => $type,
            'numeroT' => $numeroT,
            'total' => $total,
            'dateRes' => $dateRes,
        ]);
    }

    #[Route('/signature/all', name: 'app_signature_all', methods: ['GET'])]
    public function SignatureAll(Request $request){

        $etatJ = json_decode($request->query->get('etatj'));

        return $this->render("/download_signature/index.html.twig", [
            'etatJ' => $etatJ,
        ]);
    }

    
    #[Route('{id}-{type}-{numeroT}-{total}-{dateRes}/pdf', name: 'app_download_pdf', methods: ['GET','POST'])]
    public function generatepdfJournaliere($id,$type,$numeroT,$total,$dateRes,Request $request )
    {
        // dd($dateRes);
        // dd($id,$type,$numeroT,$total);

//********************** START CODE OF SIGNATURE **********************/

        $signedData = $request->request->get('signed');
        $nowS = new \DateTime();
        $dateNowS = $nowS->format('Y-m-d H:i:s');
        $dateNowSS = str_replace(':', '-', $dateNowS);
        $dateN=(string)$dateNowSS;

        //pour redirect le dernier route 
        $route = $request->headers->get('referer');


        // Save the signature image to the uploads directory
        $folderPath = "uploads/SignatureImage/";
        $image_parts = explode(";base64,", $signedData); 
        if (count($image_parts) < 2) {
            // Handle the error, e.g. by returning a response with an error message
            $this->addFlash('notif','Vous devez signer');
            return $this->redirect($route);
        }
        $image_type_aux = explode("image/", $image_parts[0]);
        if (isset($image_type_aux[1])) {
            $image_type = $image_type_aux[1];
        } else {
            // handle the error case here
            return new Response('Invalid image type', Response::HTTP_BAD_REQUEST);
        }
        if (isset($image_type)) {
            if (!is_dir($folderPath)) {
                mkdir($folderPath, 0755, true);
            }
            // $file = $folderPath . uniqid() . '.'.$image_type;
            $file = $folderPath . uniqid() . '.'.$image_type;

            $image_base64 = base64_decode($image_parts[1]);
            file_put_contents($file, $image_base64);
            $file = str_replace('public', '', $file);

        } else {
            // handle the error case here
            return new Response('Invalid image type', Response::HTTP_BAD_REQUEST);
        }

//********************** END CODE OF SIGNATURE **********************/

/*************************START CODE OF PDF *************************/

        // $html contient les Html dans twig
        $html = $this->renderView('etatjaurnaliere/showpdf.html.twig',[
         'type'=> $type, 'numeroT'=>$numeroT, 'total'=>$total, 'dateRes'=>$dateRes, 'dateNow'=>$dateN, 'file'=>$file
        ]);

        // Create a new instance of the Dompdf class
        $pdf = new Dompdf();
    
        //accepte image in pdf
        $pdf->set_option('isRemoteEnabled', true);

        // Load the HTML content into the Dompdf object
        $pdf->loadHtml($html);
    
        // Render the PDF
        $pdf->render();
    
        // Get the PDF output as a string
        $pdfContent = $pdf->output();
    
            // Write the PDF to disk
            $filename = 'EtatJournaliere_'.$dateN.'.pdf';

                // Create a temporary file to store the PDF
                $tempFilename = tempnam(sys_get_temp_dir(), 'pdf_');
                file_put_contents($tempFilename, $pdfContent);
            
                // Create a response that points to the temporary PDF file
                $response = new BinaryFileResponse(new File($tempFilename));
        
        //     // Set the response headers to force a download and set the filename
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $filename);
        
        /*************************END CODE OF PDF *************************/

        return $response;
    }

    #[Route('/All/pdf', name: 'app_download_pdfAll', methods: ['GET','POST'])]
    public function generatepdfAllJournaliere(Request $request)
    {
//********************** START CODE OF SIGNATURE **********************/

$signedData = $request->request->get('signed');
$nowS = new \DateTime();
$dateNowS = $nowS->format('Y-m-d H:i:s');
$dateNowSS = str_replace(':', '-', $dateNowS);
$dateN=(string)$dateNowSS;

//pour redirect le dernier route 
$route = $request->headers->get('referer');

// Save the signature image to the uploads directory
$folderPath = "uploads/SignatureImage/";
$image_parts = explode(";base64,", $signedData); 
if (count($image_parts) < 2) {
            // Handle the error, e.g. by returning a response with an error message
            $this->addFlash('notif','Vous devez signer');
            return $this->redirect($route);
        }
$image_type_aux = explode("image/", $image_parts[0]);
if (isset($image_type_aux[1])) {
    $image_type = $image_type_aux[1];
} else {
    // handle the error case here
    return new Response('Invalid image type', Response::HTTP_BAD_REQUEST);
}
if (isset($image_type)) {
    if (!is_dir($folderPath)) {
        mkdir($folderPath, 0755, true);
    }
    // $file = $folderPath . uniqid() . '.'.$image_type;
    $file = $folderPath . uniqid() . '.'.$image_type;

    $image_base64 = base64_decode($image_parts[1]);
    file_put_contents($file, $image_base64);
    $file = str_replace('public', '', $file);

} else {
    // handle the error case here
    return new Response('Invalid image type', Response::HTTP_BAD_REQUEST);
}

//********************** END CODE OF SIGNATURE **********************/



        $now = new \DateTime();
        $dateNow = $now->format('Y-m-d H:i:s');
        $dateNowString = str_replace(':', '-', $dateNow);

        $url=urldecode($request->query->get('etatj'));
        $etatJ = json_decode($url);;
      
        $html = $this->renderView('etatjaurnaliere/showpdf2.html.twig',[
            'etatj'=> $etatJ,'file'=>$file
           ]);
        
        // Create a new instance of the Dompdf class
        $pdf = new Dompdf();
        
        //accepte image in pdf
        $pdf->set_option('isRemoteEnabled', true);
    
        // Load the HTML content into the Dompdf object
        $pdf->loadHtml($html);
    
        // Render the PDF
        $pdf->render();
    
        // Get the PDF output as a string
        $pdfContent = $pdf->output();
        
        // Create a temporary file to store the PDF
        $tempFilename = tempnam(sys_get_temp_dir(), 'pdf_');
        file_put_contents($tempFilename, $pdfContent);
    
        // Create a response that points to the temporary PDF file
        $response = new BinaryFileResponse(new File($tempFilename));
    
        // Set the response headers to force a download and set the filename
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, "EtatJournaliere_All_{$dateNowString}.pdf");
    

        return $response;
    }
    
    
}