<?php

namespace App\Form;

use App\Entity\Certificat;
use App\Entity\Event;
use App\Entity\Participation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class CertificatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('eventFilter', EntityType::class, [
                'class' => Event::class,
                'choice_label' => 'titre',
                'label' => 'Événement',
                'mapped' => false,
                'placeholder' => 'Choisir un événement',
                'attr' => ['class' => 'form-select', 'id' => 'certificat_event_filter'],
            ])
            ->add('participation', EntityType::class, [
                'class' => Participation::class,
                'choice_label' => function (Participation $p) {
                    return $p->getUser()->getEmail();
                },
                'choice_attr' => function (Participation $p) {
                    return ['data-event-id' => $p->getEvent()->getId()];
                },
                'label' => 'Participant',
                'placeholder' => 'Choisir un événement ci-dessus pour afficher les participants',
                'attr' => ['class' => 'form-select', 'id' => 'certificat_participation'],
                'constraints' => [new NotBlank(['message' => 'Veuillez choisir un participant.'])],
            ])
            ->add('dateObtention', DateType::class, [
                'label' => 'Date d\'obtention',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('codeUnique', TextType::class, [
                'label' => 'Code unique (laissé vide pour génération auto)',
                'required' => false,
                'attr' => ['class' => 'form-control', 'placeholder' => 'Ex: CERT-2025-XXXX ou vide'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Certificat::class,
        ]);
    }
}
