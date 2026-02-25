<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationType extends AbstractType
{
    public const ROLE_ADMIN = 'ROLE_ADMIN';
    public const ROLE_ETUDIANT = 'ROLE_ETUDIANT';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => ['placeholder' => 'votre@email.com', 'class' => 'form-control'],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'first_options' => [
                    'label' => 'Mot de passe',
                    'attr' => ['placeholder' => '••••••••', 'class' => 'form-control'],
                    'constraints' => [
                        new NotBlank(['message' => 'Veuillez saisir un mot de passe']),
                        new Length(['min' => 6, 'minMessage' => 'Le mot de passe doit faire au moins {{ limit }} caractères']),
                    ],
                ],
                'second_options' => [
                    'label' => 'Confirmer le mot de passe',
                    'attr' => ['placeholder' => '••••••••', 'class' => 'form-control'],
                ],
                'invalid_message' => 'Les deux mots de passe doivent être identiques.',
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'required' => false,
                'attr' => ['placeholder' => 'Votre nom', 'class' => 'form-control'],
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'required' => false,
                'attr' => ['placeholder' => 'Votre prénom', 'class' => 'form-control'],
            ])
            ->add('role', ChoiceType::class, [
                'label' => 'Rôle',
                'mapped' => false,
                'choices' => [
                    'Étudiant' => self::ROLE_ETUDIANT,
                    'Administrateur' => self::ROLE_ADMIN,
                ],
                'attr' => ['class' => 'form-select'],
                'placeholder' => 'Choisir un rôle',
                'constraints' => [new NotBlank(['message' => 'Veuillez choisir un rôle'])],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
