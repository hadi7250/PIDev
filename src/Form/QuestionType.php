<?php

namespace App\Form;

use App\Entity\Question;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('questionText', TextType::class, [
                'label' => 'Question Text'
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Multiple Choice' => 'multiple_choice',
                    'True/False' => 'true_false',
                ],
                'label' => 'Question Type'
            ])
            // ⬇️ THIS ALLOWS YOU TO ADD MULTIPLE OPTIONS (A, B, C...)
            ->add('options', CollectionType::class, [
                'entry_type' => TextType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'label' => false,
            ])
            // ⬇️ THIS IS FOR THE CORRECT ANSWER
            ->add('correctAnswer', TextType::class, [
                'label' => 'Correct Answer (Exact Text)'
            ])
            ->add('points', IntegerType::class, [
                'label' => 'Points'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
}