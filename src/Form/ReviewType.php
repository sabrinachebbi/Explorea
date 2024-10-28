<?php

namespace App\Form;

use App\Entity\Accommodation;
use App\Entity\Activity;
use App\Entity\Reservation;
use App\Entity\Review;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('note')
            ->add('comment')
            ->add('dateReView', null, [
                'widget' => 'single_text',
            ])
            ->add('accommodation', EntityType::class, [
                'class' => Accommodation::class,
                'choice_label' => 'id',
            ])
            ->add('activity', EntityType::class, [
                'class' => Activity::class,
                'choice_label' => 'id',
            ])
            ->add('traveler', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
            ->add('reservation', EntityType::class, [
                'class' => Reservation::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Review::class,
        ]);
    }
}
