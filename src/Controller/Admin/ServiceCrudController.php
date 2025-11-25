<?php

namespace App\Controller\Admin;

use App\Entity\Service;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ServiceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Service::class;
    }

    
    public function configureFields(string $pageName): iterable
    {

        yield TextField::new('titre', 'Titre');
        yield TextEditorField::new('contenu', 'Contenu');
        // Afficher les images en back-office (liste)
        yield ImageField::new('image', 'Logo')
            ->setUploadDir('public\assets\uploads')
            ->setBasePath('assets\uploads')
            ->onlyOnIndex()
        ;
        // afficher le choix depuis la banque d'image dans les formulaires
        yield AssociationField::new('image', 'Logo')
            ->onlyOnForms();

    }
    
}
