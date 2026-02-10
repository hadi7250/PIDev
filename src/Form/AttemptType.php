<?php

namespace App\Form;

use App\Entity\Attempt;
use App\Entity\Quiz;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AttemptType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('started_at', null, [
                'widget' => 'single_text',
            ])
            ->add('completed_at', null, [
                'widget' => 'single_text',
            ])
            ->add('score')
            ->add('answers')
            ->add('quiz', EntityType::class, [
                'class' => Quiz::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Attempt::class,
        ]);
    }
}
