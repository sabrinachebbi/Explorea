<?php

namespace App\Form;

use App\Entity\Picture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Form\Type\VichImageType;

class PictureTypeActivity extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('activityImageFile', VichImageType::class, [
                'label' => false,
                'constraints' => [
                    new Assert\File([
                        'maxSize' => '2000k',
                        'mimeTypes' => ['image/jpg', 'image/png', 'image/jpeg'],
                        'mimeTypesMessage' => 'Veuillez télécharger une image valide (JPEG ou PNG ou JPEG).',
                    ])
                ]
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Picture::class,
        ]);
    }
}