<?php

namespace App\Form;

use App\Entity\Chapitre;
use App\Entity\Cours;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChapitreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Chapter Title',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Enter chapter title']
            ])
            ->add('contenu', TextareaType::class, [
                'label' => 'Content (YouTube Link or Text)',
                'attr' => ['class' => 'form-control', 'rows' => 4, 'placeholder' => 'Paste YouTube link or content here']
            ])
            ->add('cours', EntityType::class, [
                'class' => Cours::class,
                'choice_label' => 'titre', // This tells Symfony to show the Course Title in the dropdown
                'label' => 'Select Course',
                'attr' => ['class' => 'form-select'] // Bootstrap class for dropdowns
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Chapitre::class,
        ]);
    }
}