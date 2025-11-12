<?php

namespace App\Controller\Admin;

use App\Entity\Image;
use App\Entity\Article;
use App\Service\ImageService;
use Symfony\UX\Dropzone\Form\DropzoneType;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\HttpFoundation\RequestStack;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Symfony\Component\Validator\Constraints\File as FileConstraint;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ArticleCrudController extends AbstractCrudController
{
    public function __construct(
        private ImageService $imageService,
        private RequestStack $requestStack,
        private ParameterBagInterface $params ,
    ) {}

    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('titre');
        yield TextEditorField::new('contenu');
        yield DateField::new('dateCreation')
            ->onlyOnIndex();
        yield DateField::new('dateModification')
            ->onlyOnIndex();
        yield BooleanField::new('estPublie')
            ->onlyOnIndex();
        yield TextField::new('auteur')
            ->onlyOnIndex();

        // Afficher les images en back-office (liste)
        yield ImageField::new('imagePrincipale')
            ->setUploadDir('public\assets\uploads')
            ->setBasePath('assets\uploads')
            ->onlyOnIndex()
        ;
        yield ImageField::new('imageDeux')
            ->setUploadDir('public\assets\uploads')
            ->setBasePath('assets\uploads')
            ->onlyOnIndex()
        ;
        yield ImageField::new('imageTrois')
            ->setUploadDir('public\assets\uploads')
            ->setBasePath('assets\uploads')
            ->onlyOnIndex()
        ;
        yield ImageField::new('imageQuatre')
            ->setUploadDir('public\assets\uploads')
            ->setBasePath('assets\uploads')
            ->onlyOnIndex()
        ;

        // afficher le choix depuis la banque d'image dans les formulaires
        yield AssociationField::new('imagePrincipale')
            ->onlyOnForms();
        yield AssociationField::new('imageDeux')
            ->onlyOnForms();
        yield AssociationField::new('imageTrois')
            ->onlyOnForms();
        yield AssociationField::new('imageQuatre')
            ->onlyOnForms();
    }
}