<?php

namespace App\Form;

use App\Entity\Accommodation;
use App\Enum\propertyType;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccommodationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',\Symfony\Component\Form\Extension\Core\Type\TextType::class, [
                    'required' => false
                ])
            ->add('description')
            ->add('address')
            ->add('priceNight')
            ->add('NbGuests')
            ->add('NbRooms')
            ->add('propertyType',EnumType::class, [
                'class' => propertyType::class,
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