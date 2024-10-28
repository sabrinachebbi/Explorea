<?php

namespace App\Form;

use App\Entity\Activity;
use App\Entity\Reservation;
use App\Repository\ActivityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

class ReservationActivityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var Reservation $reservation */
        $reservation = $options['data'];
        $builder
            ->add('activities', EntityType::class, [
                'class' => Activity::class,
                'choice_label' => 'title',
                'multiple' => true,
                'expanded' => true,
                'attr' => [
                    'class' => 'activity-checkboxes',
                ],
                'query_builder' => function (ActivityRepository $activityRepository) use ($reservation) {
                    return $activityRepository
                        ->createQueryBuilder('a')
                        ->where('a.city = :city')
                        ->setParameter('city', $reservation->getAccommodation()->getCity());
                }
            ]);


    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
