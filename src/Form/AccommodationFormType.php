<?php

namespace App\Form;

use App\Entity\Accommodation;
use App\Entity\City;
use App\Entity\Country;
use App\Enum\propertyType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
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
               ->add('city', EntityType::class, [
                   'class' => City::class,
                   'choice_label' => 'name',
                   'placeholder' => 'Sélectionnez une ville',
               ])
            ->add('address', TextType::class, [
                'required' => false,
                'label' => 'Adresse',
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'label' => 'Description',
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
            ->add('pictures', FileType::class, [
                'label' => 'Images',
                'multiple' => true,
                'mapped' => false,
                'required' => false,
                'attr' => ['accept' => 'image/*'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Accommodation::class,
        ]);
    }
}
