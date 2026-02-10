<?php

namespace App\Form;

use App\Entity\Discussion;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class DiscussionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Discussion Title',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter discussion title',
                    'autocomplete' => 'off',
                    'novalidate' => 'novalidate'
                ],
                'required' => false
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Content',
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 6,
                    'placeholder' => 'Enter discussion content',
                    'autocomplete' => 'off',
                    'novalidate' => 'novalidate'
                ],
                'required' => false
            ])
            ->add('authorName', TextType::class, [
                'label' => 'Your Name',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter your name',
                    'autocomplete' => 'off',
                    'novalidate' => 'novalidate'
                ],
                'required' => false
            ])
            ->add('category', EntityType::class, [
                'label' => 'Category',
                'class' => Category::class,
                'choice_label' => 'name',
                'required' => false,
                'placeholder' => 'Select a category',
                'attr' => [
                    'class' => 'form-select'
                ]
            ])
        ;

        // Add admin-only fields
        if ($options['is_admin']) {
            $builder
                ->add('isPinned', CheckboxType::class, [
                    'label' => 'Pin this discussion',
                    'required' => false,
                    'attr' => [
                        'class' => 'form-check-input'
                    ]
                ])
                ->add('isLocked', CheckboxType::class, [
                    'label' => 'Lock this discussion',
                    'required' => false,
                    'attr' => [
                        'class' => 'form-check-input'
                    ]
                ])
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Discussion::class,
            'is_admin' => false,
        ]);
    }
}
