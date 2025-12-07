<?php

namespace App\Twig\Components;

use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;

#[AsLiveComponent]
final class Burgermenu
{
    use DefaultActionTrait;
    #[LiveProp(writable: true)]
    public bool $isOpen = false;

    #[LiveAction]
    public function toggleMenu(): void{
        $this->isOpen =! $this->isOpen;
    }
}
