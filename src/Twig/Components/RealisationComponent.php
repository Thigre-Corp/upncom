<?php

namespace App\Twig\Components;

use App\Entity\Realisation;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class RealisationComponent
{
    public Realisation $realisation;
    public string $class ='';
    public string $id;

    public function getRealisation(): Realisation
    {
        return $this->realisation;
    }
    public function getClass(): string
    {
        return $this->class;
    }
    public function getId():string
    {
        return $this->id;
    }
}