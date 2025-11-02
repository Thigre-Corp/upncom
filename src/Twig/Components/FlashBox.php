<?php
/*
    Composant Twig pour les messages Flash
*/
namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class FlashBox
{
    public string $type ='success';
    public string $message ='';
}