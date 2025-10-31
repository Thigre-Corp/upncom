<?php

namespace App\Controller;

use App\Form\SubscriberType;
use Symfony\Component\Uid\Uuid;
use App\Entity\Newsletters\Subscriber;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class NewslettersController extends AbstractController
{
    #[Route('/newsletters', name: 'app_newsletters')]
    public function index(
        Request $request, 
        EntityManagerInterface $entityManager,  
        MailerInterface $mailer, 
        ): Response
    {
        $subscriber = new Subscriber();
        $form = $this->createForm(SubscriberType::class, $subscriber);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $subscriber->setDateCreation(new \DateTime());
            $subscriber->setToken(Uuid::v4());

            $entityManager->persist($subscriber);
            $entityManager->flush();

            // generate a signed url and email it to the user
            $email = (new TemplatedEmail())
                ->from('contact@upncom.fr')
                ->to($subscriber->getEmail())
                ->subject('NewsLetter Up\'n\'Com: pensez à valider votre adresse mail !')
                ->htmlTemplate('emails/subscriber.html.twig')
                ->context(['subscriber' => $subscriber]);
            
            $mailer->send($email);

            return $this->redirectToRoute('home');
        }

        return $this->render('newsletters/index.html.twig', [
            'controller_name' => 'NewslettersController',
            'form' => $form,
        ]);
    }

        #[Route('/newsletters/validate/{id}/{token}', name: 'app_newsletters_validate')]
    public function validate(Subscriber $subscriber, $token , EntityManagerInterface $entityManager): Response
    {
        if($subscriber->getToken() != $token){
            $this->addFlash('message', 'error!!!');
            dd('pas ok');
            return $this->redirectToRoute('home');
        }
        $subscriber->setIsValid(true);

        $entityManager->persist($subscriber);
        $entityManager->flush();
        
        $this->addFlash('message', 'validate!!!');
        return $this->redirectToRoute('home');
    }
}
