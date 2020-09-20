<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Color;
use App\Entity\SubCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('color', EntityType::class, [
                'class' => Color::class,
                'mapped' => false,
                'multiple' => true,
                'expanded' => true
    ])
            ->add('category',EntityType::class, [
                'class' => Category::class,
                'mapped' => false,
                'multiple' => true,
                'expanded' => true
            ])
            ->add('subCategory',EntityType::class,[
                'class' => SubCategory::class,
                'mapped' => false,
                'multiple' => true,
                'expanded' => true
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
