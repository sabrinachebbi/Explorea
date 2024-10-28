<?php

namespace App\Form\administration;

use App\Entity\ReservationStatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationStatusType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('status', TextType::class, [
                'label' => 'Statut de réservation',
                'attr' => [
                    'placeholder' => 'Entrez le statut (ex: En attente, Confirmée, Annulée)',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ReservationStatus::class,
        ]);
    }
}
