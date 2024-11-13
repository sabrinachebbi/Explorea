<?php
namespace App\Form;

use App\Entity\Activity;
use App\Entity\Reservation;
use App\Repository\ActivityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationActivityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $city = $options['city']; // On suppose que 'city' est toujours défini

        $builder
            ->add('activities', EntityType::class, [
                'class' => Activity::class,
                'choice_label' => 'title',
                'multiple' => true,
                'expanded' => true,
                'attr' => [
                    'class' => 'activity-checkboxes',
                ],
                'query_builder' => function (ActivityRepository $activityRepository) use ($city) {
// Requête directe pour les activités dans la même ville
                    return $activityRepository->createQueryBuilder('a')
                        ->where('a.city = :city')
                        ->setParameter('city', $city);
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);

        $resolver->setRequired('city'); // Déclare l'option 'city' comme obligatoire
    }
}
