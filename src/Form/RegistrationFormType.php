<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nsc', IntegerType::class, [
                'label' => 'NSC (Numéro Étudiant)',
                'constraints' => [
                    new NotBlank(['message' => 'Le NSC est requis']),
                    new Positive(['message' => 'Le NSC doit être positif']),
                ],
                'attr' => ['class' => 'form-control', 'placeholder' => '123456']
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'constraints' => [
                    new NotBlank(['message' => 'L\'email est requis']),
                ],
                'attr' => ['class' => 'form-control', 'placeholder' => 'exemple@esprit.tn']
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom Complet',
                'constraints' => [
                    new NotBlank(['message' => 'Le nom est requis']),
                    new Length([
                        'min' => 2,
                        'max' => 100,
                        'minMessage' => 'Le nom doit contenir au moins {{ limit }} caractères',
                        'maxMessage' => 'Le nom ne peut pas dépasser {{ limit }} caractères'
                    ]),
                ],
                'attr' => ['class' => 'form-control', 'placeholder' => 'John Doe']
            ])
            ->add('plainPassword', RepeatedType::class, [
                'mapped' => false, // AJOUTÉ - IMPORTANT
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe doivent correspondre.',
                'options' => ['attr' => ['class' => 'form-control']],
                'required' => true,
                'first_options'  => [
                    'label' => 'Mot de passe',
                    'attr' => ['placeholder' => 'Minimum 6 caractères']
                ],
                'second_options' => [
                    'label' => 'Répéter le mot de passe',
                    'attr' => ['placeholder' => 'Répétez votre mot de passe']
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Le mot de passe est requis']),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'J\'accepte les conditions d\'utilisation',
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter nos conditions.',
                    ]),
                ],
                'attr' => ['class' => 'form-check-input']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}