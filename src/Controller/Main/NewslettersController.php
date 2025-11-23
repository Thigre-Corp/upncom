<?php
/*
Controller de la Newsletter
    routes:
        'app_newsletters' -> index() // @TODO - à supprimer, incorporer le tout en pied de page.
            - s'inscrire à la newsletter via un formulaire
            - crée un Objet Subscriber avec un token unique (UUID-V4)
            - déclanche l'envoi d'un mail de validation à l'adresse fournie
        'app_newsletters_validate' -> validate(Subscriber $subscriber, string $token, EntityManagerInterface $entityManager)
            - Récupère en GET l'id du subscriber et hydrate l'objet
            - Rcupère le token du subscriber et le compare avec celui reçu en GET
            - si cohérent, on valide le Subscriber, le persist en DB, et on confirme l'action en flash en retournant vers 'home'
            - si incohérent, on informe d'une erreur en Flash en retournant vers 'home'
        'app_newsletters_unsubscribe' -> unsubscribe(Subscriber $subscriber, $token, EntityManagerInterface $entityManager)
            - Récupère en GET l'id du subscriber et hydrate l'objet
            - Rcupère le token du subscriber et le compare avec celui reçu en GET
            - si cohérent, on remove le Subscriber de la DB, et on confirme l'action en flash en retournant vers 'home'
            - si incohérent, on informe d'une erreur en Flash en retournant vers 'home'
*/

namespace App\Controller\Main;

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
    public function subscribeForm(
        Request $request,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer,
        ): Response 
    {
        $subscriber = new Subscriber();
        $form = $this->createForm(SubscriberType::class, $subscriber, [
            'action' => $this->generateUrl('app_newsletters')
        ]);

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
            $this->addFlash('message', 'Surveillez votre boîte mail, vous aller recevoir un email pour confirmer votre inscription à la newsletter d\' Up\'n\'Com');

            return $this->redirectToRoute('home');
        }

        return $this->render('newsletters/index.html.twig', [
            'controller_name' => 'NewslettersController',
            'form' => $form,
        ]);
    }

    #[Route('/newsletters/validate/{id}/{token}', name: 'app_newsletters_validate')]
    public function validate(Subscriber $subscriber, string $token, EntityManagerInterface $entityManager): Response
    {
        if ($subscriber->getToken() != $token) {
            $this->addFlash('message', 'Votre demande \'a pu être réalisée.
                Si vous avez cliqué sur un lien, tentez de faire un copie/coller de ce dernier
                directement dans la barre d\'adresse de votre navigateur');
            return $this->redirectToRoute('home');
        }
        $subscriber->setIsValid(true);

        $entityManager->persist($subscriber);
        $entityManager->flush();

        $this->addFlash('message', 'Félicitations!!! Vous faites dorénavant parti du club très select des inscrits à la newsletter d\' Up\'n\'Com');
        return $this->redirectToRoute('home');
    }

    #[Route('/newsletters/sedesabonner/{id}/{token}', name: 'app_newsletters_unsubscribe')]
    public function unsubscribe(Subscriber $subscriber, string $token, EntityManagerInterface $entityManager): Response
    {
        if ($subscriber->getToken() != $token) {
            $this->addFlash('message', 'Votre demande \'a pu être réalisée.
                Si vous avez cliqué sur un lien, tentez de faire un copie/coller de ce dernier
                directement dans la barre d\'adresse de votre navigateur');
            return $this->redirectToRoute('home');
        }
        $subscriber->setIsValid(true);

        $entityManager->remove($subscriber);
        $entityManager->flush();

        $this->addFlash('message', 'Votre email a été supprimé de notre base de données:
            vous ne recevrez plus notre newsletter.
            Vous pouvez vous réinscrire à loisir');
        return $this->redirectToRoute('home');
    }
}
