<?php

namespace App\Controller\Admin;

use App\Entity\Client;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ClientCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Client::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('nom', 'Nom commecial du Client');
        yield UrlField::new('clientURL', 'Lien vers le site du client');
        yield AssociationField::new('realisations', 'Réalisations')
            ->setFormTypeOption('by_reference', false);
        // Afficher les images en back-office (liste)
        yield ImageField::new('imageClient', 'Logo Client')
            ->setUploadDir('public\assets\uploads')
            ->setBasePath('assets\uploads')
            ->onlyOnIndex()
        ;
        // afficher le choix depuis la banque d'image dans les formulaires
        yield AssociationField::new('imageClient', 'Logo Client')
            ->onlyOnForms();
    }
}
