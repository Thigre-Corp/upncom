<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Security\EmailVerifier;

use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManager;
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
        public function __construct(private EmailVerifier $emailVerifier)
    {
    }
    
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer ): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $entityManager->persist($contact);
            $entityManager->flush();

            // generate a signed url and email it to the user
            $email = (new Email())
                ->from('no-reply@upncom.fr')
                ->to($contact->getEmail())
                //->cc('cc@example.com')
                //->bcc('bcc@example.com')
                //->replyTo('fabien@example.com')
                //->priority(Email::PRIORITY_HIGH)
                ->subject($contact->getNomContact().', pensez à valider votre adresse mail !')
                ->text('putain de mail de test!')
                ->html('<a href="localhost:8000/verify/your-email?id='.$contact->getId().'">Pour que votre message laissé sur le site up\'n\'com soit remis à son destinataire, cliquez sur ce lien </a>');

            $mailer->send($email);

            return $this->redirectToRoute('home');
        }


        return $this->render('contact/index.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/verify/your-email', name: 'app_contact_email')]
    public function verifyContactEmail(Request $request, ContactRepository $contactRepository, EntityManagerInterface $em, MailerInterface $mailer): Response
    {
        //récupère l'ID de l'utilisateur
        $id = $request->query->get('id'); // voir pour uuid.

        // en l'absence, retour case départ.
        if (null === $id) {
            return $this->redirectToRoute('home'); // message flash par-ci
        }

        // récupère le contact en BDD
        $contact = $contactRepository->find($id);

        // en l'absence, retour case départ.
        if (null === $contact) {
            return $this->redirectToRoute('home'); // message flash par-là
        }

        // si le message a déja été validé
        if ($contact->isEmailValide()){
            return $this->redirectToRoute('home'); // message flash là encore
        }
        // sinon...
            $contact->setEmailValide(true);
            $em->persist($contact);
            $em->flush();

        // en enfin , envoyé le message à la patrone
            $email = (new Email())
                ->from('demandes-via@upncom.fr')
                ->to('contact@upncom.fr')
                ->replyTo($contact->getEmail())
                //->priority(Email::PRIORITY_HIGH)
                ->subject($contact->getNomContact().' a pris contact sur ton site !')
                ->text('resumé de la situation: '.$contact->getContenu())
                ->html('<p>See Twig integration for better HTML integration!</p>');

            $mailer->send($email);

        // done: Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('home');
    }
}




/* //çà sert plus à rien.... 
    #[Route('/contact/ville_ud', name:'app_city_update' , methods: ['POST'])]
    public function cityUpdate(Request $request): JsonResponse //|Response
    {
       $jsonInside = new IsJsonRequestMatcher();
       if($jsonInside->matches($request)) {
            $data = json_decode($request->getContent(), true);
            //send $data somewhere, over the rainbow.
            return new JsonResponse(['yes' => $data]);
       }

        return new JsonResponse(['oups' => 'oupa']);
    }*/