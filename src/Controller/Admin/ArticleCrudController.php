<?php

namespace App\Controller\Admin;

use Twig\Markup;
use App\Entity\Article;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ArticleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            //IdField::new('id'),
            TextField::new('titre'),
            TextEditorField::new('contenu')->formatValue(fn (string $value) => new Markup($value, 'UTF-8')),
            DateTimeField::new('dateCreation'),
            ImageField::new('mediaURL')
             ->setUploadDir('assets/img/articles')
             ->setBasePath('assets/img/articles')
             ->setUploadedFileNamePattern('[slug]-[timestamp].[extension]'),

        ];
    }
    
}
