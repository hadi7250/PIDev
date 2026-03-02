<?php

namespace App\Form;

use App\Entity\ForumMessage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ReplyMessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Write your reply...',
                    'rows' => 3,
                    'class' => 'form-control',
                    'style' => 'background: white; color: #333; border: 1px solid #ddd; border-radius: 5px; padding: 8px;'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ForumMessage::class,
            'csrf_protection' => false, // Disable CSRF for dynamic reply forms
        ]);
    }
}
