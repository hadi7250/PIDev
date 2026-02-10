<?php

namespace App\Form;

use App\Entity\Question;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('questionText', TextareaType::class, [
                'label' => 'Question Text',
                'attr' => ['class' => 'form-control', 'rows' => 3]
            ])
            ->add('points', IntegerType::class, [
                'label' => 'Points',
                'attr' => ['class' => 'form-control', 'value' => 1]
            ])
            
            // OPTIONS (Not mapped directly to entity, handled in Controller)
            ->add('option1', TextType::class, [
                'mapped' => false,
                'label' => 'Option A',
                'attr' => ['class' => 'form-control']
            ])
            ->add('option2', TextType::class, [
                'mapped' => false,
                'label' => 'Option B',
                'attr' => ['class' => 'form-control']
            ])
            ->add('option3', TextType::class, [
                'mapped' => false,
                'label' => 'Option C',
                'attr' => ['class' => 'form-control']
            ])
            ->add('option4', TextType::class, [
                'mapped' => false,
                'label' => 'Option D',
                'attr' => ['class' => 'form-control']
            ])

            // Select which option is correct
            ->add('correctAnswerChoice', ChoiceType::class, [
                'mapped' => false, 
                'label' => 'Correct Answer',
                'choices' => [
                    'Option A' => 0,
                    'Option B' => 1,
                    'Option C' => 2,
                    'Option D' => 3,
                ],
                'attr' => ['class' => 'form-select']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
}