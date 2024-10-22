<?php

namespace App\Form;

use App\Entity\Activity;
use App\Entity\Reservation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

class ReservationActivityFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('departureDate', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de départ',
                'attr' => [
                    'class' => 'form-control',
                    'min' => (new \DateTime())->format('Y-m-d'), /*la date d'aujourd'hui comme date minimal*/
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez sélectionner une date de départ']),
                    new GreaterThanOrEqual([
                        'value' => 'today',
                        'message' => 'La date de départ doit être aujourd\'hui ou plus tard.',
                    ]),
                ],
            ])
            ->add('voyagerNb', IntegerType::class, [
                'label' => 'Nombre de voyageurs',
                'attr' => [
                    'class' => 'form-control',
                    'min' => 1,
                ]
            ])
            ->add('activities', EntityType::class, [
                'class' => Activity::class,
                'choice_label' => 'title',
                'multiple' => true,
                'expanded' => true,
                'attr' => [
                    'class' => 'activity-checkboxes',
                ]
            ]);


    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
