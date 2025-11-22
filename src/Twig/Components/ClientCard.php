<?php

namespace App\Twig\Components;

use App\Entity\Client;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class ClientCard
{
    public ?Client $client;
    public string $class ="";
}
