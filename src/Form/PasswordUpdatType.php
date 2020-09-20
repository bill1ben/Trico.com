<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PasswordUpdatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPassword',PasswordType::class,[
                'label' => 'Ancien mot de pass',
                'attr' => [
                    'placeholder' => 'donner mot de pass'
                ]
            ])
            ->add('newPassword',PasswordType::class,[
                'label' => 'Nouveau mot de pass',
                'attr' => [
                    'placeholder' => 'taper votre nouveau mot de pass'
                ]
            ])
            ->add('confirmPassword',PasswordType::class,[
                'label' => 'confirmation mot de pass',
                'attr' => [
                    'placeholder' => 'confirmez votre nouveau mot de pass'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
