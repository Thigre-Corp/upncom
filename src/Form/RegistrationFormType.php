<?php

namespace App\Form;

use App\Entity\User;
use App\Form\Extension\TogglePasswordTypeExtension;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'email@domaine.com',
                ],
                ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'Je m\'engage à ne pas communiquer mon mot de passe, sous aucun pretexte !',
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Je m\'engage à ne pas communiquer mon mot de passe, sous aucun pretexte !',
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'first_options'  => [
                    'toggle' => true,
                    'row_attr' => [
                        'id' => 'the_one',
                    ],
                    'label' => 'Saisir un mot de passe',
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'Saisir un mot de passe',
                    ],
                ],
                'second_options' => [
                    'toggle' => true,
                    'row_attr' => [
                        'id' => 'the_two',
                    ],
                    'label' => 'Répéter le mot de Passe',
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'Répéter le mot de Passe',
                    ],
                ],
                'constraints' => [
                    new Regex([
                        // recommandation cnil - 12 caractères; Maj, Min, Chiffres, Spéciaux... OK
                        'pattern' => '/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{12,}/',
                        'match' => true,
                        'message' => 'Le mot de passe doit comporter 12 caractères, incluant majuscules, minuscules, chiffres et caratères spéciaux usuels...',
                    ])
                ],
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
