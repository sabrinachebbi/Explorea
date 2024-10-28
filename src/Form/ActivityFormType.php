<?php

namespace App\Form;

use App\Entity\Activity;
use App\Entity\Category;
use App\Entity\City;
use App\Entity\Country;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActivityFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'required' => false,
                'label' => 'Titre',
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'label' => 'Description',
            ])
            ->add('price', NumberType::class, [
                'label' => 'Prix ',
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

            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
            ])
            ->add('duration', IntegerType::class, [
                'required' => true,
                'label' => 'Durée',
            ])
            ->add('pictures', CollectionType::class, [
                'entry_type' => PictureTypeActivity::class, // Formulaire pour une seule image
                'allow_add' => true, // Permet d'ajouter des images
                'allow_delete' => true, // Permet de supprimer des images
                'by_reference' => false,
                'label' => 'Images',
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Activity::class,
        ]);
    }
}
