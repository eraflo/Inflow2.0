<?php

namespace App\Form;

use App\Entity\Articles;
use App\Entity\Categories;
use App\Form\CategoriesType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class CreateArticleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        //dd($options['categories']);
        $builder
            ->add('title', TextType::class)
            ->add('content', CKEditorType::class)
            ->add('release_date', DateType::class, [
                'data' => new \DateTime(),
                'empty_data' => [
                    'year' => date('Y'),
                    'month' => date('m'),
                    'day' => date('d'),
                ],
            ])
            ->add('description', TextType::class)
            ->add('categories', ChoiceType::class, [
                'choices' => $options['categories'],
                'mapped' => false,
                'choice_label' => 'name',
                'choice_value' => 'id',
                //'multiple' => true,
                'attr' => [
                    'class' => 'categories',
                ],
            ])
            ->add('includes', ChoiceType::class, [
                'choices' => $options['categories'],
                'mapped' => false,
                'choice_label' => 'name',
                'choice_value' => 'id',
                'multiple' => true,
                'attr' => [
                    'class' => 'includes',
                ],
            ])
            /* ->add('includes', IntegerType::class, [
                'entry_type' => CategoriesType::class,
                'allow_add' => true,
                'mapped' => false,
            ]) */
            /*
            ->add('includes', CollectionType::class, [
                'entry_type' => CategoriesType::class,
                'allow_add' => true,
                'mapped' => false,
            ])
            */
            /*
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
            */
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
