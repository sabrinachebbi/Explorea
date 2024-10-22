<?php
namespace App\Form;

use App\Entity\Category;
use App\Entity\Country;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActivityFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options):void
    {
        $builder

            ->add('country', EntityType::class, [
                'class' => Country::class,
                'choice_label' => 'name',
                'placeholder' => 'Choisissez un pays',
                'required' => false,
                'label' => 'Pays'
            ])

            ->add('duration', IntegerType::class, [
                'required' => false,
                'attr' => ['placeholder' => 'Durée (en jours)']
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'required' => false,
                'placeholder' => 'Choisissez une catégorie',
            ])

            ->add('priceMin', IntegerType::class, [
                'required' => false,
                'label' => false,
            ])

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