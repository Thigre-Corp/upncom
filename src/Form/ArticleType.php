<?php

namespace App\Form;

use App\Entity\Tag;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('contenu')
            ->add('dateCreation')
            ->add('isPublished')
            ->add('tags', EntityType::class, [
                'class' => Tag::class,
                'choice_label' => 'tag_name',
                'multiple' => true,
            ])
            ->add('users', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'nom',
            ])
            ->add('images', EntityType::class, [
                'class' => Image::class,
                'choice_label' => 'mediaURL',
                'mapped' => false,
    
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
