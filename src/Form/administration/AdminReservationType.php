<?php


namespace App\Form\administration;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('departureDate', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de départ',
            ])
            ->add('returnDate', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de retour',
                'required' => false, // Si c'est optionnel pour certaines réservations
            ])
            ->add('voyagerNb', IntegerType::class, [
                'label' => 'Nombre de voyageurs',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
