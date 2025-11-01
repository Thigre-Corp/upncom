<?php

namespace App\Message;

use Symfony\Component\Messenger\Attribute\AsMessage;

#[AsMessage('async')]
final class SendNewsletterMessage
{
    /*
     * Add whatever properties and methods you need
     * to hold the data for this message class.
     */

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
