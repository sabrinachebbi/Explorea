<?php
namespace App\Form;

use App\Entity\Country;
use App\Enum\propertyType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccommodationFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // Champ pour filtrer par pays (entité Country)
            ->add('country', EntityType::class, [
                'class' => Country::class,
                'choice_label' => 'name',
                'placeholder' => 'Choisissez un pays',
                'required' => false,
                'label' => 'Pays'
            ])

            // Champ pour filtrer par type de propriété (enum propertyType)
            ->add('propertyType', ChoiceType::class, [
                'choices' => [
                    'Appartement' => propertyType::Apartment,
                    'Maison' => propertyType::House,
                ],
                'placeholder' => 'Choisissez un type de propriété',
                'required' => false,
                'label' => 'Type de propriété',
            ])

            // Prix minimum
            ->add('priceMin', IntegerType::class, [
                'required' => false,
                'label' => false,
            ])

            // Prix maximum
            ->add('priceMax', IntegerType::class, [
                'required' => false,
                'label' => false

            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null, // Ce n'est pas lié directement à une entité
        ]);
    }
}