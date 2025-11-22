<?php

namespace App\Twig\Components;

use App\Entity\Service;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class ServiceCard
{
    public ?Service $service;
    public ?string $page = "";
    public string $class= "";

    
}
