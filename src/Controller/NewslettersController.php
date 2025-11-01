<?php

namespace App\Controller;

use App\Form\SubscriberType;
use Symfony\Component\Uid\Uuid;
use App\Entity\Newsletters\Newsletter;
use App\Entity\Newsletters\Subscriber;
use App\Message\SendNewsletterMessage;
use App\Service\SendNewsletterService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Messenger\MessageBusInterface;

final class NewslettersController extends AbstractController
{
    #[Route('/newsletters', name: 'app_newsletters')]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer,
    ): Response {
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
    public function validate(Subscriber $subscriber, $token, EntityManagerInterface $entityManager): Response
    {
        if ($subscriber->getToken() != $token) {
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

    #[Route('/newsletters/send', name :'app_newsletters_send')]
    public function sendTodayNewsLetter(MessageBusInterface $bus, EntityManagerInterface $entityManager): response
    {
        $newsletters = $entityManager->getRepository(Newsletter::class)->findToBeSentToday();
            
        if ($newsletters == null){
            $this->addFlash('message','pas de newsletter aujourd\'hui!!!');
            return $this->redirectToRoute('home');
        }
            
        $subscribers = $entityManager->getRepository(Subscriber::class)->findBy(['isValid' => true]);

        if ($subscribers == null){
                    $this->addFlash('message','pas d\'inscrit à qui envoyer la newsletter !!!');
                    return $this->redirectToRoute('home');
        }

        foreach ($newsletters as $newsletter) {
            foreach ($subscribers as $subscriber){
                $bus->dispatch(new SendNewsletterMessage($newsletter->getId(), $subscriber->getId()));
            }
            $newsletter->setIsSent(true);
            $entityManager->persist($newsletter);
            $entityManager->flush();
        }

        $this->addFlash('message','newsletter(s) envoyée(s)!');
        return $this->redirectToRoute('home');
    }
}
