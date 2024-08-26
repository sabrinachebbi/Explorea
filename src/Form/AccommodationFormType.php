<?php

namespace App\Form;

use App\Entity\Accommodation;
use App\Enum\propertyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AccommodationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('title', TextType::class, [
                'required' => false,
                'label' => 'Titre',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse',
            ])
            ->add('priceNight', NumberType::class, [
                'label' => 'Prix Nuité',
            ])
            ->add('NbGuests', NumberType::class, [
                'label' => 'Nb Invités',
            ])
            ->add('NbRooms', NumberType::class, [
                'label' => 'Pièces',
            ])
            ->add('propertyType', EnumType::class, [
                'class' => propertyType::class,
                'label' => 'Type de Bien',
                'placeholder' => 'Sélectionnez un type',
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