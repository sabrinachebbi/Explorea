<?php

namespace App\Form;

use App\Entity\Accommodation;
use App\Entity\City;
use App\Enum\propertyType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Doctrine\ORM\EntityRepository;


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
                'group_by' => function (City $city) {
                    return $city->getCountry()->getName();
                },
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->join('c.country', 'country')
                        ->orderBy('country.name', 'ASC')
                        ->addOrderBy('c.name', 'ASC');
                },
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
            ->add('pictures', CollectionType::class, [
                'entry_type' => PictureTypeAccommodation::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'required' =>false,
                'entry_options' => [
                    'attr' => ['class' => 'picture-class'],
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Accommodation::class,
        ]);
    }
}
