<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\NotBlank(['message' => 'Email is required']),
                    new \Symfony\Component\Validator\Constraints\Email(['message' => 'Please enter a valid email']),
                ],
            ])
            ->add('name', TextType::class, [
                'label' => 'Name',
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\NotBlank(['message' => 'Name is required']),
                    new \Symfony\Component\Validator\Constraints\Length([
                        'min' => 2,
                        'max' => 100,
                        'minMessage' => 'Name must be at least {{ limit }} characters',
                        'maxMessage' => 'Name cannot be longer than {{ limit }} characters'
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'required' => true,
                'first_options' => [
                    'label' => 'Password',
                    'attr' => ['autocomplete' => 'new-password']
                ],
                'second_options' => [
                    'label' => 'Confirm Password',
                    'attr' => ['autocomplete' => 'new-password']
                ],
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\NotBlank(['message' => 'Password is required']),
                    new \Symfony\Component\Validator\Constraints\Length([
                        'min' => 6,
                        'max' => 4096,
                        'minMessage' => 'Password must be at least {{ limit }} characters',
                        'maxMessage' => 'Password cannot be longer than {{ limit }} characters'
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
