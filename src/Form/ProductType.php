<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('price')
            ->add('content')
            ->add('colors')
            ->add('categories')
            ->add('subCategories')
            ->add('image', FileType::class,[
                'label' => 'cover image : ',
                'mapped' => false,
                'required' => false
            ])
            ->add('images', FileType::class,[
                'label' => "image collection",
                'multiple' => true,
                'mapped' => false,
                'required' => false
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
