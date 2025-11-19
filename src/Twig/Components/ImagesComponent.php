<?php 
/*
    Twig Component to manage images
    permet de:
        - proposer une image par défaut en cas d'absence d'objet image
        - met en place les balises et textes alternatifs si le fichier est de type webp ou svg
        - met une croix rouge si le fichier ne correspond pas à ces deux possibilités 
            ->(fichiers autres que webp et svg ne peuvent pas sortir de ImageUploadService...)
*/
namespace App\Twig\Components;

use App\Entity\Image;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class ImagesComponent
{
    public ?Image $image;
    public ?string $class;

    public function getImage(): string
    {
        return $this->image;
    }
    public function getClass(): string
    {
        return $this->class;
    }
}