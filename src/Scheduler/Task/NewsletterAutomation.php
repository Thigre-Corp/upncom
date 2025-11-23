<?php
/*
    Tâche d' automatisation de la gestion des la newsletter
        - Scheduler: default
        - récurence: 1 fois par jour
    Logique:   
        - Si au moins une Newsletter est à envoyer ce jour
        - Et Si au moins un Subscriber est valid
        - Alors on générer des SendNewsletterMessage avec les Newsletter::id et Subscriber::id concernées
        - purge les Subscriber qui n'ont pas validé leur mails après 1 jour.
*/

namespace App\Scheduler\Task;

use App\Entity\Newsletters\Newsletter;
use App\Entity\Newsletters\Subscriber;
use App\Message\SendNewsletterMessage;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Scheduler\Attribute\AsPeriodicTask;

#[AsPeriodicTask(frequency: 30)] // ('1 day')
class NewsletterAutomation
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private MessageBusInterface $bus,
    ) {}

    public function __invoke()
    {
        $nonValidSubscribers = $this->entityManager->getRepository(Subscriber::class)->findBy(['isValid' => false]);

        if ($nonValidSubscribers !== null)
        {
            foreach ($nonValidSubscribers as $nonValidSubscriber) {
                if ($nonValidSubscriber->getDateCreation() < new DateTime('yesterday')){
                    $this->entityManager->remove($nonValidSubscriber);
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
