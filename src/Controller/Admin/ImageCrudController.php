<?php

namespace App\Controller\Admin;

use App\Entity\Image;
use App\Form\ImageType;
use Symfony\UX\Dropzone\Form\DropzoneType;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ImageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Image::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            Field::new('file')
                ->setFormType(VichImageType::class)
                //->addFormTheme('@Dropzone/form_theme.html.twig')
                /*->addFormTheme('@Dropzone/form_theme.html.twig')
                ->setFormTypeOptions([
                        'data_class' => null,
                    ])*/
                ,
            TextField::new('altText'),
            TextField::new('name'),
            //TextEditorField::new('description'),
        ];
    }
    
}
