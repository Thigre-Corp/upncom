<?php

namespace App\Controller\Admin;

use Twig\Markup;
use App\Entity\Newsletters\Newsletter;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;

class NewsletterCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Newsletter::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('titre'),
            TextEditorField::new('contenu')->formatValue(fn (string $value) => new Markup($value, 'UTF-8')),
            DateTimeField::new('dateCreation'),
            DateTimeField::new('datePublication'),
            BooleanField::new('isSent'),
        ];
    }
    
}
