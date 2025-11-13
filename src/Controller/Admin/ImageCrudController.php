<?php

namespace App\Controller\Admin;

use App\Entity\Image;
use Symfony\UX\Dropzone\Form\DropzoneType;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ImageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Image::class;
    }

    public function configureFields(string $pageName): iterable
    {
        
        yield ImageField::new('mediaURL')
            ->setBasePath('assets/uploads/')
            ->setUploadDir('public/assets/uploads/')
            ->onlyOnIndex()
            ;
        yield Field::new('imageFile', 'Images à uploader')
            ->setFormType(DropzoneType::class)
            ->setFormTypeOptions([
                'required' => false,
                ])
                ->onlyOnForms()
                ;
        yield TextField::new('altText');
    }
    
}
