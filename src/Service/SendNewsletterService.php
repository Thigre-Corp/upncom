<?php

namespace App\Service;

use App\Entity\Newsletters\Newsletter;
use App\Entity\Newsletters\Subscriber;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class SendNewsletterService{
    private $mailer;

    public function __construct(MailerInterface $mailer){
        $this->mailer = $mailer;
    }
    public function sendNlSub(Newsletter $newsletter, Subscriber $subscriber ): void {

            $email = (new TemplatedEmail())
                ->from('contact@upncom.fr')
                ->to($subscriber->getEmail())
                ->subject('NewsLetter Up\'n\'Com: pensez à valider votre adresse mail !')
                ->htmlTemplate('emails/newsletter.html.twig')
                ->context([
                    'subscriber' => $subscriber,
                    'newsletter' => $newsletter,
                    ]);
            
            $this->mailer->send($email);
    }
}