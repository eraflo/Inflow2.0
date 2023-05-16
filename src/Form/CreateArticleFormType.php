<?php

namespace App\Form;

use App\Entity\Articles;
use App\Entity\Categories;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class CreateArticleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class)
            ->add('content', CKEditorType::class)
            ->add('release_date', DateType::class)
            ->add('description', TextType::class)
            ->add('includes', EntityType::class, [
                'class' => Categories::class,
                'choices' => $options['categories'],
                'choice_label' => 'name',
                'choice_value' => 'id',
                'multiple' => true,
                'expanded' => false,
                'required' => true,
            ])
            ->add('concerns', CollectionType::class, [
                'entry_type' => TextType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'delete_empty' => false,
                'prototype' => true,
                'prototype_options'  => [
                    'help' => 'You can enter a new name here.',
                ],
                'prototype_name'  => 'addtag',
                'required' => false,
                'entry_options' => [
                    'label' => 'My Array Item',
                    'attr' => [
                        'class' => 'form-control',
                        'required' => false,
                        'empty_data' => null,
                    ],
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Créer',
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Créer',
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Articles::class,
            'categories' => [],
        ]);
        $resolver->setAllowedTypes('categories', 'array');
    }
}
