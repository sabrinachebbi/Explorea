<?php

namespace App\Form;

use App\Entity\Accommodation;
use App\Entity\Activity;
use App\Entity\Reservation;
use App\Entity\ReservationStatus;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('departureDate', null, [
                'widget' => 'single_text',
            ])
            ->add('returnDate', null, [
                'widget' => 'single_text',
            ])
            ->add('dateCreation', null, [
                'widget' => 'single_text',
            ])
            ->add('dateModification', null, [
                'widget' => 'single_text',
            ])
            ->add('total')
            ->add('voyagerNb')
            ->add('status', EntityType::class, [
                'class' => ReservationStatus::class,
                'choice_label' => 'id',
            ])
            ->add('activities', EntityType::class, [
                'class' => Activity::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
            ->add('traveler', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
            ->add('accommodation', EntityType::class, [
                'class' => Accommodation::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
