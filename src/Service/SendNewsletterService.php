<?php
/*
            Service d'envoi de la newsletter
            génére l'envoi d'un email selon le template ('emails/newsletter.html.twig')
            envoyer à: Subscriber
            contenu : Newsletter 
*/
namespace App\Service;

use App\Entity\Newsletters\Newsletter;
use App\Entity\Newsletters\Subscriber;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class SendNewsletterService{
    private $mailer;
    private $bodyRenderer;

    public function __construct(
        MailerInterface $mailer,
        ){
        $this->mailer = $mailer;
        $this->bodyRenderer = $bodyRenderer;
    }

    public function sendNlSub(Newsletter $newsletter, Subscriber $subscriber ): void {

            $email = (new TemplatedEmail())
                ->from('contact@upncom.fr')
                ->to($subscriber->getEmail())
                ->subject('NewsLetter Up\'n\'Com: '.$newsletter->getTitre())
                ->htmlTemplate('emails/newsletter.html.twig')
                ->context([
                    'subscriber' => $subscriber,
                    'newsletter' => $newsletter,
                    ]);
            $this->mailer->send($email);
    }
}