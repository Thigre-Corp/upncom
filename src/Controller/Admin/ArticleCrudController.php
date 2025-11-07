<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Service\ImageService;
use Symfony\UX\Dropzone\Form\DropzoneType;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\HttpFoundation\RequestStack;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Symfony\Component\Validator\Constraints\File as FileConstraint;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ArticleCrudController extends AbstractCrudController
{
    public function __construct(
        private ImageService $imageService,
        private RequestStack $requestStack
    ) {}

    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('titre');
        yield TextEditorField::new('contenu');
        yield DateField::new('dateCreation');

        yield Field::new('uploadedImages', 'Images')
            ->setFormType(DropzoneType::class)
            ->setFormTypeOptions([
                'mapped' => false,
                'multiple' => true,
                'constraints' => [
                    new All([
                        new FileConstraint([
                            'maxSize' => '5M',
                            'mimeTypes' => [
                                'image/jpeg',
                                'image/png',
                                'image/webp',
                                'image/svg+xml'
                            ],
                        ]),
                    ]),
                ],
            ])
            ->onlyOnForms();

        // Afficher les images en back-office (liste)
        yield AssociationField::new('images')
            ->onlyOnIndex();
            //->setTemplatePath('admin/fields/image_preview.html.twig');
    }

    public function persistEntity(\Doctrine\ORM\EntityManagerInterface $entityManager, $entityInstance): void
    {
        $request = $this->requestStack->getCurrentRequest();
        $files = $request->files->get('Article')['uploadedImages'] ?? [];

        if ($entityInstance instanceof Article && !empty($files)) {
            foreach ($files as $file) {
                if ($file instanceof UploadedFile) {
                    $imageEntity = $this->imageService->standardizator($file, $entityInstance->getTitre());
                    $entityInstance->addImage($imageEntity);
                }
            }
        }

        parent::persistEntity($entityManager, $entityInstance);
    }
}