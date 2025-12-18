<?php
/*
    Tâche d' automatisation de la gestion des la newsletter
        - Scheduler: default
        - récurence: 1 fois par jour
    Logique:   
        - Si au moins une Newsletter est à envoyer ce jour
        - Et Si au moins un Subscriber est valid
        - Alors on générer des SendNewsletterMessage avec les Newsletter::id et Subscriber::id concernées
        - purge les Subscriber et les contacts qui n'ont pas validé leur mails après plus d'1 jour.
*/

namespace App\Scheduler\Task;

use DateTime;
use App\Entity\Contact;
use App\Entity\Newsletters\Newsletter;
use App\Entity\Newsletters\Subscriber;
use App\Message\SendNewsletterMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Scheduler\Attribute\AsPeriodicTask;

#[AsPeriodicTask(frequency: '1 day' )]  // ( passer à 30 pour appel toute les 30 seconde dans le cadre de la démo - '1 day' sinon)
class NewsletterAutomation
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private MessageBusInterface $bus,
    ) {}

    public function __invoke()
    {
        /*commencer par la purge des contacts et subscriber
        qui n'ont pas confirmé leur adresse mail dans les délais*/
        $nonValidSubscribers = $this->entityManager
            ->getRepository(Subscriber::class)->findBy(['isValid' => false]);
        $nonValidContacts =  $this->entityManager
            ->getRepository(Contact::class)->findBy(['emailValide' => false]);

        if ($nonValidSubscribers !== null)
        {
            foreach ($nonValidSubscribers as $nonValidSubscriber) {
                if ($nonValidSubscriber->getDateCreation() < new DateTime('yesterday')){
                    $this->entityManager->remove($nonValidSubscriber);
                    $this->entityManager->flush();
                }
            }
        }
        if ($nonValidContacts !== null)
        {
            foreach ($nonValidContacts as $nonValidContact) {
                if ($nonValidContact->getDateCreation() < new DateTime('yesterday')){
                    $this->entityManager->remove($nonValidContact);
                    $this->entityManager->flush();
                }
            }
        }


        $newsletters = $this->entityManager->getRepository(Newsletter::class)->findToBeSentToday();
        if ($newsletters == null) {
            return;
        }

        $subscribers = $this->entityManager->getRepository(Subscriber::class)->findBy(['isValid' => true]);

        if ($subscribers == null) {
            return;
        }

        foreach ($newsletters as $newsletter) {
            foreach ($subscribers as $subscriber) {
                $this->bus->dispatch(new SendNewsletterMessage($newsletter->getId(), $subscriber->getId()));
            }
            $newsletter->setIsSent(true);
            $this->entityManager->persist($newsletter);
            $this->entityManager->flush();
        }
    }
}
