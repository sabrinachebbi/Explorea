<?php

namespace App\Form\administration;

use App\Entity\Accommodation;
use App\Entity\City;
use App\Entity\User;
use App\Enum\propertyType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccommodationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('address')
            ->add('priceNight')
            ->add('NbGuests')
            ->add('NbRooms')
            ->add('propertyType', EnumType::class, [
                'class' => propertyType::class,
                'choices' => propertyType::cases(),
            ])
            ->add('host', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'email',
            ])
            ->add('city', EntityType::class, [
                'class' => City::class,
                'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Accommodation::class,
        ]);
    }
}
