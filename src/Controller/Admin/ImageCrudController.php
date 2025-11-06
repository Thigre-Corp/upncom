<?php

namespace App\Controller\Admin;

use App\EasyAdmin\MyImageField;
use App\Entity\Image;
use App\Form\ImageType;
use Symfony\UX\Dropzone\Form\DropzoneType;
use Doctrine\Common\Collections\Collection;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ImageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Image::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            MyImageField::new('mediaURL')
                ->setFormType(ImageType::class)
                
                /*->setFormTypeOptions([
                        'data_class' => null,
                    ])*/
                ,
           // TextField::new('altText'),
            // TextField::new('name'),
            //TextEditorField::new('description'),
        ];
    }
    
}
