<?php

namespace App\Controller\Main;

use DateTime;
use App\Entity\Contact;
use App\Entity\Newsletters\Subscriber;
use App\Form\ContactType;

use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestMatcher\IsJsonRequestMatcher;

final class ContactController extends AbstractController
{
    public function __construct(private MailerInterface $mailer) {}

    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $contact->setDateCreation(new DateTime('now'));
            $contact->setToken(Uuid::v4());
            $entityManager->persist($contact);
            if ($contact->isAccepteNewsletter()) {
                $subscriber = new Subscriber;
                $subscriber->setEmail($contact->getEmail())
                    ->setDateCreation($contact->getDateCreation())
                    ->setToken($contact->getToken())
                    ->setIsValid(false);
                $entityManager->persist($subscriber);
            }
                $entityManager->flush();
            
            

            // generate a signed url and email it to the user
            $email = (new TemplatedEmail())
                ->from('no-reply@upncom.fr')
                ->to($contact->getEmail())
                ->subject($contact->getNomContact() . ', pensez à valider votre adresse mail pour qu\' Up\'n\'Com reçoive votre meessage !')
                ->htmlTemplate('emails/contactValider.html.twig')
                ->context(['contact' => $contact],
                ['subscriber' => $subscriber ?? null]);

            $this->mailer->send($email);
            $this->addFlash('message', 'Surveillez votre boîte mail, vous aller recevoir un email pour confirmer votre inscription à la newsletter d\' Up\'n\'Com');


            return $this->redirectToRoute('home');
        }


        return $this->render('contact/index.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/contact/validate/{id}/{token}', name: 'app_contact_validate')]
    public function validate(Contact $contact, string $token, EntityManagerInterface $entityManager): Response
    {
        if ($contact->getToken() != $token) {
            $this->addFlash('message', 'Votre demande \'a pu être réalisée.
                Si vous avez cliqué sur un lien, tentez de faire un copie/coller de ce dernier
                directement dans la barre d\'adresse de votre navigateur');
            return $this->redirectToRoute('home');
        }
        $contact->setIsValid(true);

        $entityManager->persist($contact);

        $flashMessage = 'Merci, votre message a bien été envoyé: nous reviendrons vers vous dans les plus 
                brefs délais.';
        if ($contact->isAccepteNewsletter()) {
            $subscriber = $entityManager->getRepository(Subscriber::class)->findOneByEmail($contact->getEmail());
            $subscriber->setIsValid(true);
            $entityManager->persist($subscriber);
            $flashMessage .= 'Et bravo pour votre inscrition à la newsletter';
        }
        $entityManager->flush();
        // en enfin , envoyé le message à la patrone
        $email = (new Email())
            ->from('demandes-via@upncom.fr')
            ->to('contact@upncom.fr')
            ->replyTo($contact->getEmail())
            ->priority(Email::PRIORITY_HIGH)
            ->subject($contact->getNomContact() . ' a pris contact sur ton site !')
            ->text('resumé de la situation: ' . $contact->getContenu())
            ->html('resumé de la situation: ' . $contact->getContenu());
        $this->mailer->send($email);

        // done: Change the redirect on success and handle or remove the flash message in your templates

        $this->addFlash('message', $flashMessage);
        return $this->redirectToRoute('home');
    }
}
