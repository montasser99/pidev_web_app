<?php
// src/Controller/SmsController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Twilio\Rest\Client;

class SmsController extends AbstractController
{
    public function sendSms(Client $twilio, String $numero, String $ticket): Response
    {
        $message = $twilio->messages->create(
            $numero, // recipient phone number
            array(
                'from' => '12764444912',
                'body' => 'RANOUMA KHARYOUNA!' . $ticket
                )
        );
        return new Response('SMS sent: ' . $message->sid);
    }
}
?>