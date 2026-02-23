<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $isEdit = $options['edit_mode'] ?? false;
        
        $builder
            ->add('nsc', IntegerType::class, [
                'label' => 'NSC',
                'required' => false, // Changed to false to disable HTML5 validation
                'attr' => ['class' => 'form-control']
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => false, // Changed to false to disable HTML5 validation
                'attr' => ['class' => 'form-control']
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom Complet',
                'required' => false, // Changed to false to disable HTML5 validation
                'attr' => ['class' => 'form-control']
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'Rôles',
                'choices' => [
                    'Étudiant' => 'ROLE_STUDENT',
                    'Enseignant' => 'ROLE_ENSEIGNANT',
                    'Administrateur' => 'ROLE_ADMIN',
                ],
                'multiple' => true,
                'expanded' => true,
                'required' => false, // Changed to false to disable HTML5 validation
                'attr' => ['class' => 'form-check']
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Statut',
                'choices' => [
                    'Actif' => 'active',
                    'Inactif' => 'inactive',
                    'En attente' => 'pending',
                ],
                'required' => false, // Changed to false to disable HTML5 validation
                'attr' => ['class' => 'form-control']
            ])
            ->add('photoFile', FileType::class, [
                'label' => 'Photo de profil (JPG/PNG, max 2Mo)',
                'mapped' => false, // Pas mappé directement
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2m',
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'Seulement JPG ou PNG',
                    ])
                ],
                'attr' => ['class' => 'form-control', 'accept' => 'image/*']
            ]);

        if (!$isEdit) {
            $builder->add('plainPassword', PasswordType::class, [
                'label' => 'Mot de passe',
                'required' => false, // Changed to false to disable HTML5 validation
                'mapped' => false, // AJOUTÉ
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                        'max' => 4096,
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Minimum 6 caractères'
                ]
            ]);
        } else {
            $builder->add('plainPassword', PasswordType::class, [
                'label' => 'Nouveau mot de passe (laisser vide pour ne pas changer)',
                'required' => false,
                'mapped' => false, // AJOUTÉ
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Laisser vide pour garder le mot de passe actuel'
                ]
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'edit_mode' => false,
        ]);

        $resolver->setAllowedTypes('edit_mode', 'bool');
    }
}