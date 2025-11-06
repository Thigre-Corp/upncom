<?php

namespace App\Controller\Admin;

use Twig\Markup;
use App\Entity\Article;
use App\Form\ImageType;
use Symfony\UX\Dropzone\Form\DropzoneType;
use App\Controller\Admin\ImageCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use Symfony\Component\Validator\Constraints\File;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ArticleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    public function configureFields(string $pageName): iterable
    {
        
            //IdField::new('id'),
        yield TextField::new('titre');
        yield TextEditorField::new('contenu')->formatValue(fn (string $value) => new Markup($value, 'UTF-8'));
        yield DateField::new('dateCreation');
        yield CollectionField::new('images')
                //->onlyOnForms()
                ->setEntryType(ImageType::class);
               // ->useEntryCrudForm(ImageCrudController::class)
               // ->setFormTypeOption('multiple', true) 
                //->addFormTheme('@Dropzone/form_theme.html.twig')
            //->addAssetMapperEntries('@symfony/stimulus-bundle' )
            //->addCssFiles('@symfony/ux-dropzone/style.min.css')
            //->setHelp('Fichiers images (Jpeg, png, svg webP) uniquement')
            /* ->setFormTypeOption('constraints', [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/svg',
                            'image/webp'
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image. '
                    ])
            ])*/

    }
}
