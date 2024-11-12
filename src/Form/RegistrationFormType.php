<?php

namespace App\Form;

use App\Entity\User;
use App\Enum\GenderEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('role', ChoiceType::class, [
                'choices' => [
                    'Voyageur' => 'ROLE_TRAVELER',
                    'Hôte' => 'ROLE_HOST',
                ],
                'label' => ' ',
                'expanded' => true,
                'multiple' => false,
                'mapped' => false,
            ])
            ->add('email',EmailType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer votre adresse e-mail',
                    ]),
            ],
                'required' => true, // Obligatoire côté client
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
          ->add('password', RepeatedType::class, [
              'type' => PasswordType::class,
              'invalid_message' => 'The password fields must match.',
              'options' => ['attr' => ['class' => 'password-field']],
              'required' => true,
              'first_options'  => ['label' => 'Mot de passe'],
              'second_options' => ['label' => 'Confirmer le mot de passe'],
              'constraints' => [
                  new NotBlank(['message' => 'Veuillez entrer un mot de passe']),
                  new Length([
                      'min' => 5,
                      'minMessage' => 'Le mot de passe doit contenir au moins {{ limit }} caractères.'
          ]),
            ],
            ])

            ->add('lastName', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer votre nom']),
                    new Length([
                        'min' => 5,
                        'minMessage' => 'Le nom doit contenir au moins {{ limit }} caractères.',
                        'max' => 20,
                        'maxMessage' => 'Le nom ne peut pas dépasser {{ limit }} caractères.'
                    ]),
                ],
                'label' => 'Nom',
                'required' => false,
                'mapped'=> false,
            ])
            ->add('firstName', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer votre prénom']),
                    new Length([
                        'min' => 5,
                        'minMessage' => 'Le prénom doit contenir au moins {{ limit }} caractères.',

                        'max' => 20,
                        'maxMessage' => 'Le prénom ne peut pas dépasser {{ limit }} caractères.'
                    ]),
                ],
                'label' => 'Prénom',
                'required' => true,
                'mapped'=>false,
            ])
            ->add('gender', ChoiceType::class, [
                'choices' => [
                    'Homme' => GenderEnum::Male,
                    'Femme' => GenderEnum::Female,
                    'Autre' => GenderEnum::Other,
                ],
                'label' => false,
                'expanded' => true, //  boutons radio
                'multiple' => false,
                'mapped' => false,

            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
