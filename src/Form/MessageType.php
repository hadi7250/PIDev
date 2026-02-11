<?php

namespace App\Form;

use App\Entity\Message;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class MessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextareaType::class, [
                'label' => 'Message',
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 4,
                    'placeholder' => 'Enter your message',
                    'autocomplete' => 'off',
                    'novalidate' => 'novalidate'
                ],
                'required' => false
            ])
            ->add('author', EntityType::class, [
                'label' => 'Author',
                'class' => User::class,
                'choice_label' => 'name',
                'required' => false,
                'placeholder' => 'Select author (optional)',
                'attr' => [
                    'class' => 'form-select'
                ]
            ])
        ;

        // Add admin-only fields
        if ($options['is_admin']) {
            $builder
                ->add('isAuthor', CheckboxType::class, [
                    'label' => 'Mark as discussion author',
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
            'data_class' => Message::class,
            'is_admin' => false,
        ]);
    }
}
