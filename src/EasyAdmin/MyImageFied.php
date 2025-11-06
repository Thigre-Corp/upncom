<?php
namespace App\EasyAdmin;

use App\Form\ImageType;
use Symfony\UX\Dropzone\Form\DropzoneType;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;


class MyImageField implements FieldInterface
{
    use FieldTrait;
    
    public static function new(string $propertyName, ?string $label = null)
    {
        return (new self())
            ->setFormType(ImageType::class)
            ->setProperty($propertyName)
            ->setLabel($label)
            ->addFormTheme('@Dropzone/form_theme.html.twig')
            ;
    }
}