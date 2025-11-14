<?php

namespace App\Controller\Admin;

use App\Entity\Realisation;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class RealisationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Realisation::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('accroche', 'Accroche');
        yield TextEditorField::new('resumeMission', 'Résumé de la mission');
        yield TextEditorField::new('description', 'Description de la mission');
        yield AssociationField::new('clients', 'Clients');
        // Afficher les images en back-office (liste)
        yield ImageField::new('imageCouverture', 'Illustration Principale')
            ->setUploadDir('public\assets\uploads')
            ->setBasePath('assets\uploads')
            ->onlyOnIndex()
        ;
        // afficher le choix depuis la banque d'image dans les formulaires
        yield AssociationField::new('imageCouverture', 'Illustration Principale')
            ->onlyOnForms();
        // images autres;
        yield AssociationField::new('images', 'Illustrations Secondaires');
    }
    
}
