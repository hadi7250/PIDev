<?php

namespace App\Form;

use App\Entity\Evaluation;
use App\Entity\Competence;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class EvaluationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'attr' => ['class' => 'form-control']
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Type',
                'choices' => [
                    'Exam' => 'exam',
                    'Quiz' => 'quiz',
                    'Projet' => 'project',
                    'Oral' => 'oral',
                    'Devoir' => 'homework',
                ],
                'attr' => ['class' => 'form-control']
            ])
            ->add('competence', EntityType::class, [
                'label' => 'Compétence',
                'class' => Competence::class,
                'choice_label' => 'name',
                'attr' => ['class' => 'form-control']
            ])
            ->add('evaluationDate', DateTimeType::class, [
                'label' => 'Date de l\'évaluation',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control']
            ])
            ->add('weight', NumberType::class, [
                'label' => 'Poids (%)',
                'required' => false,
                'attr' => ['class' => 'form-control', 'min' => 0, 'max' => 100]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'attr' => ['class' => 'form-control', 'rows' => 3]
            ])
            ->add('language', ChoiceType::class, [
                'label' => 'Programming Language (for compiler)',
                'choices' => [
                    'PHP' => 'php',
                    'Python' => 'python',
                    'JavaScript' => 'javascript',
                    'Java' => 'java',
                    'C++' => 'cpp',
                    'C#' => 'csharp',
                    'Go' => 'go',
                    'Rust' => 'rust',
                ],
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evaluation::class,
        ]);
    }
}
