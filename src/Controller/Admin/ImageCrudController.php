<?php

namespace App\Controller\Admin;

use App\Entity\Image;
use Symfony\UX\Dropzone\Form\DropzoneType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
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
    
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->setPermission(Action::NEW, 'ROLE_ADMIN')
            ->setPermission(Action::EDIT, 'ROLE_ADMIN')
            ->setPermission(Action::DELETE, 'ROLE_ADMIN')
            ->setPermission(Action::BATCH_DELETE, 'ROLE_ADMIN')
        ;
    /*        public const BATCH_DELETE = 'batchDelete';
    public const DELETE = 'delete';
    public const DETAIL = 'detail';
    public const EDIT = 'edit';
    public const INDEX = 'index';
    public const NEW = 'new';
    public const SAVE_AND_ADD_ANOTHER = 'saveAndAddAnother';
    public const SAVE_AND_CONTINUE = 'saveAndContinue';
    public const SAVE_AND_RETURN = 'saveAndReturn';*/
    }
}
