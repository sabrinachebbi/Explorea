<?php
namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;


class ReservationAccommodationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('departureDate', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de départ',
                'attr' => [
                    'class' => 'form-control',
                    'min' => (new \DateTime())->format('Y-m-d'), // Définit aujourd'hui comme date minimale
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez sélectionner une date de départ']),
                    new GreaterThanOrEqual([
                        'value' => 'today',
                        'message' => 'La date de départ doit être aujourd\'hui ou plus tard.',
                    ]),
                ],
            ])
            ->add('returnDate', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de retour',
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez sélectionner une date de retour']),
                ],
            ])
            ->add('voyagerNb', IntegerType::class, [
                'label' => 'Nb voyageurs',
                'constraints' => [
                    new GreaterThanOrEqual([
                        'value' => 1,
                        'message' => 'Le nombre de voyageurs doit être d\'au moins 1',
                    ]),
                ],
            ]);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
