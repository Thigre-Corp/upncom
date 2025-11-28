<?php

namespace App\Form;

use App\Entity\Newsletters\Subscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubscriberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Je veux m\'inscrire à la newslettet en inscrivant mon mail ici'
            ])
            ->add('rgpd', CheckboxType::class, [
                'required' => true,
                'mapped' =>false,
                'label' => 'En cochant cette case'
            ])
            ->add('valider', SubmitType::class, [
                'label' => 'Je valide en cliquant ICI !',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Subscriber::class,
        ]);
    }
}
