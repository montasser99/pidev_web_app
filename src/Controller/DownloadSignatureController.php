<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DownloadSignatureController extends AbstractController
{
    /**
     * @Route("/indexI", name="signature")
     */
    public function index(){

        return $this->render("/download_signature/index.html.twig");
        
    }
    /**
     * @Route("/download-signature", name="download_signature")
     */
    public function downloadSignature(Request $request)
    {
        $signedData = $request->request->get('signed');

        // en va changer par sessionName
        $name="montasser-benouirane";
        //en va changer par sessionCIN 
        $cin=13025486;
        //dateNow
        $now = new \DateTime();
        $dateNow = $now->format('Y-m-d-H:i:s');
        $dateNowString = str_replace(':', '-', $dateNow);

        // Save the signature image to the uploads directory
        $folderPath = "uploads/SignatureImage/";
        $image_parts = explode(";base64,", $signedData); 
        if (count($image_parts) < 2) {
            // Handle the error, e.g. by returning a response with an error message
            return new Response('Invalid signature data', Response::HTTP_BAD_REQUEST);
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

      return $this->redirectToRoute("app_etatjaurnaliere");
    }
}