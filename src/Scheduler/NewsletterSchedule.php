<?php

namespace App\Scheduler;

use App\Scheduler\Task\NewsletterAutomation;
use Symfony\Component\Scheduler\Attribute\AsSchedule;
use Symfony\Component\Scheduler\RecurringMessage;
use Symfony\Component\Scheduler\Schedule;
use Symfony\Component\Scheduler\ScheduleProviderInterface;
use Symfony\Contracts\Cache\CacheInterface;

#[AsSchedule('default')]
final class MainSchedule implements ScheduleProviderInterface
{
    public function __construct(
        private CacheInterface $cache,
    ) {
    }

    public function getSchedule(): Schedule
    {
        return (new Schedule())
           /* ->add(
                RecurringMessage::cron(
                    '0 0 * * *',
                    new NewsletterAutomation

                )
            )*/
            ->stateful($this->cache)
            ->processOnlyLastMissedRun(true)
        ;
    }
}
