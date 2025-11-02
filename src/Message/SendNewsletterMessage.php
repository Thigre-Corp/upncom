<?php
/*
            Message d'envoi de la newsletter
            Serialise les ID de la newsletter et du subscriber
            et entre en file d'attente pour traitement async(hrone)
*/
namespace App\Message;

use Symfony\Component\Messenger\Attribute\AsMessage;

#[AsMessage('async')]
final class SendNewsletterMessage
{

    private int $newsletterId;
    private int $subscriberId;

    public function __construct(
        int $newsletterId,
        int $subscriberId
    ) 
    {
        $this->newsletterId = $newsletterId;
        $this->subscriberId = $subscriberId;
    }

    public function getNewsletterId(): int{
        return $this->newsletterId;
    }
    public function getSubscriberId(): int{
        return $this->subscriberId;
    }
}
