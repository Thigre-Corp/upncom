<?php
/*
            Message Hander d'envoi de la newsletter
            Récupère les objet subscriber et newsletter depuis leurs sID serialisés dans le message
            puis exécute le service ad-hoc
*/
namespace App\MessageHandler;

use App\Entity\Newsletters\Newsletter;
use App\Entity\Newsletters\Subscriber;
use App\Message\SendNewsletterMessage;
use App\Service\SendNewsletterService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class SendNewsletterMessageHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private SendNewsletterService $sendNewsletterService,
    ) {}

    public function __invoke(
        SendNewsletterMessage $message,
    ): void {

        $subscriber = $this->entityManager->getRepository(Subscriber::class)->findOneById($message->getSubscriberId());
        $newsletter = $this->entityManager->getRepository(Newsletter::class)->findOneById($message->getNewsletterId());
        $this->sendNewsletterService->sendNlSub($newsletter, $subscriber);
    }
}
