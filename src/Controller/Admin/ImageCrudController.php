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
        if($pageName != "new"){
            yield ImageField::new('mediaURL', "Nom du Fichier Image sur le Serveur")
                ->setBasePath('assets/uploads/')
                ->setHelp('non modifiable')
                ->setUploadDir('public/assets/uploads/')
                ->setDisabled(true)
                ;
        }
        else{
            yield Field::new('imageFile', 'Images à uploader')
                ->setFormType(DropzoneType::class)
                ->setFormTypeOptions([
                    'required' => true,
                    ])
                    ->onlyOnForms()
                    ;
        }
        yield TextField::new('altText', 'Texte Alternatif')
            ->setFormTypeOptions([
                    'required' => true,
            ]);
    }
    
}
