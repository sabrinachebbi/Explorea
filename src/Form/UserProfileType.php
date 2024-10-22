<?php

namespace App\Form;

use App\Entity\UserProfile;
use App\Enum\GenderEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EnumType;

class UserProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('FirstName')
            ->add('lastName')
            ->add('dateBirth', null, [
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('phone')
            ->add('country')
            ->add('city')
            ->add('address')
            ->add('Gender', EnumType::class, [
                'class' => GenderEnum::class,
                'choices' => GenderEnum::cases(),

                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserProfile::class,
        ]);
    }
}
