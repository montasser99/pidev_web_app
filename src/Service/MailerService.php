<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerService
{
    private MailerInterface $mailer;
    private string $replyTo;

    public function __construct(MailerInterface $mailer, string $replyTo)
    {
        $this->mailer = $mailer;
        $this->replyTo = $replyTo;
        
    }
    public function sendEmail(
        $to = 'chayma.gheribi@gmail.com',
        $content = '<p>See Twig integration for better HTML integration!</p>',
        $subject = 'TakTak Transport:Welcome to our website'
    ): void
    {
        $email = (new Email())
            ->from('ghribi.chaima@esprit.com')
            ->to($to)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            ->replyTo($this->replyTo)
            //->priority(Email::PRIORITY_HIGH)
            ->subject($subject)
//            ->text('Sending emails is fun again!')
            ->html($content);
             $this->mailer->send($email);
        // ...
    }

}