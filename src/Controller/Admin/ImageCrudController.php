<?php

namespace App\Controller\Admin;

use App\EasyAdmin\MyImageField;
use App\Entity\Image;
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
                ->setUploadDir('public/assets/uploads/');
                //-> onlyOnIndex()
                ;
        yield ImageField::new('mediaURL')
                ->setBasePath('assets/uploads/')
                ->onlyOnDetail()
                ;
       // yield MyImageField::new('mediaURL')
        //    ;
    }
    
}
