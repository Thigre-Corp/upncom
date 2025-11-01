<?php

namespace App\Scheduler\Task;

use App\Entity\Newsletters\Newsletter;
use App\Entity\Newsletters\Subscriber;
use App\Message\SendNewsletterMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Scheduler\Attribute\AsPeriodicTask;

#[AsPeriodicTask(frequency: 60)]
class NewsletterAutomation
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private MessageBusInterface $bus,
    ) {}

    public function __invoke()
    {
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
