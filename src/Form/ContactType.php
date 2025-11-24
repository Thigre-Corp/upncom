<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('raisonSociale', TextType::class, [
                'label' => 'Raison Sociale de votre entreprise',
            ])
            ->add('nomContact', TextType::class, [
                'label' => 'Votre nom',
            ])
            ->add('telephone', TextType::class, [
                'label' => 'Votre numéro de téléphone',
            ])
            ->add('email', EmailType::class , [
                'label' => 'Votre eMail',
            ])
            ->add('contenu' , TextareaType::class, [
                'label' => 'Votre message',
            ])
            ->add('accepteNewsletter', CheckboxType::class, [
                'label' => 'J\'en profite pour m\'abonner à la newsletter',
            ])
            ->add('rgpd', CheckboxType::class, [
                'label' => 'J\'accepte que mes données soient utilisées
                    dans le cadre de l\'envoi de la newsletter conformément
                    à la politique de confidentialité.',
                'mapped' => false,
                'required' => true,
            ])
            ->add('cp', NumberType::class, [
                'required' => true,
                'label' => 'Votre code postal',
            ])
            ->add('ville', ChoiceType::class,[
                    'choices'  => [
                        ],
                'attr' => ['placeholder' => 'Choisir une ville dans la liste'],
                'label' => 'Selectionner votre ville dans la liste',
            ] )
            ->add('envoyer', SubmitType::class)
            ->get('ville')->resetViewTransformers()
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
