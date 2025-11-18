<?php
/*
    Scheduleur pour l'automatisation de la Newsletter :
        - créele Scheduler 'default' utiliser par la Task NewsletterAutomation
        - la récurence d'exécution de la tâche se trouve dans NewsletterAutomation.php
*/

namespace App\Scheduler;

use Symfony\Component\Scheduler\Attribute\AsSchedule;
use Symfony\Component\Scheduler\Schedule;
use Symfony\Component\Scheduler\ScheduleProviderInterface;
use Symfony\Contracts\Cache\CacheInterface;

#[AsSchedule('default')]
final class NewsletterSchedule implements ScheduleProviderInterface
{
    public function __construct(
        private CacheInterface $cache,
    ) {}

    public function getSchedule(): Schedule
    {
        return (new Schedule())
            ->stateful($this->cache)
            ->processOnlyLastMissedRun(true)
        ;
    }
}
