<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class MediaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('media', FileType::class, [
                'required' => false,
                'label' => 'Fichier photo en liens avec le produit',
                'attr' => [
                    'onChange' => 'loadFile(event)'
                ],
                'data_class' => null,
                'constraints' => [
                    new File([
                        'maxSize' => '2000k', 'maxSizeMessage' => 'Fichier trop volumineux, 2MO maximum',
                        'mimeTypes' => ['image/jpg', 'image/jpeg', 'image/png', 'image/web'],
                        'mimeTypesMessage' => "formats autorisé:image/jpg','image/jpeg','image/png','image/web'"
                    ])
                ]
            ])
            ->add('Ajouter', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
