<?php

namespace App\Form;

use App\Entity\UserProfile;
use App\Enum\GenderEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EnumType;

class UserProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', null, [
                'required' => true,
                'attr' => [
                    'maxlength' => 50, // Limite de 50 caractères
                    'title' => 'Le prénom ne doit contenir que des lettres ',
                ],
            ])
            ->add('lastName', null, [
                'required' => true,
                'attr' => [
                    'maxlength' => 50, // Limite de 50 caractères
                    'title' => 'Le nom ne doit contenir que des lettres ',
                ],
            ])
            ->add('dateBirth', null, [
                'required' => false,
                'widget' => 'single_text',
                'html5' => true,
                'attr' => [
                    'max' => date('Y-m-d'), // Empêche les dates futures
                ],
            ])
            ->add('phone', null, [
                'required' => true,
                'attr' => [
                    'pattern' => '\d{10}', // Accepte uniquement 10 chiffres
                    'title' => 'Le numéro de téléphone doit contenir exactement 10 chiffres',
                ],
            ])
            ->add('country', null, [
                'required' => true,
                'attr' => [
                    'maxlength' => 50, // Limite de 50 caractères
                ],
            ])
            ->add('city', null, [
                'required' => true,
                'attr' => [
                    'maxlength' => 50, // Limite de 50 caractères
                ],
            ])
            ->add('address', null, [
                'required' => true,
                'attr' => [
                    'maxlength' => 100, // Limite de 100 caractères
                ],
            ])
            ->add('gender', ChoiceType::class, [
                'label' => 'Genre',
                'choices' => [
                    'Homme' => GenderEnum::Male,
                    'Femme' => GenderEnum::Female,
                    'Autre' => GenderEnum::Other,
                ],
                'expanded' => true, // Pour afficher les options en boutons radio
                'multiple' => false,
            ]);
    }


        public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserProfile::class,
        ]);
    }
}
