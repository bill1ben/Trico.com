<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class,[
                'label' => 'Prénom',
                'attr' => [
                    'placeholder' => 'Votre prénom ...'
                ]
            ])
            ->add('lastName',TextType::class,[
        'label' => 'Nom',
        'attr' => [
            'placeholder' => 'Votre nom de famille ...'
        ]
    ])
            ->add('Email',EmailType::class,[
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'Votre adress email'
                ]
            ])
            ->add('picture',UrlType::class,[
                'label' => 'Url de votre profil',
                'attr' => [
                    'placeholder' => 'Url de votre avatar'
                ]
            ])
            ->add('hash',PasswordType::class,[
                'label' => 'mot de pass',
                'attr' => [
                    'placeholder' => 'mot de pass'
                ]
            ])
            ->add('passwordConfirm',PasswordType::class,[
                'label' => 'confirmation de mot de pass',
                'attr' => [
                    'placeholder' => 'confirmer votre mot de pass'
                ],
            ])

            ->add('intro',TextType::class,[
                'label' => 'Introduction',
                'attr' => [
                    'placeholder' => ' présentez vous en quelques mots .. '
                ]
            ])
            ->add('description',TextareaType::class,[
                'label' => 'description dètaillèe',
                'attr' => [
                    'placeholder' => 'a vous de vous presenter en details '
                ]
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
