<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Unit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('price')
            ->add('productMedia', FileType::class, [
                'required' => false,
                'label' => 'Fichier photo en liens avec le produit',
                'attr' => [
                    'onChange' => 'loadFile(event)'
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '2000k', 'maxSizeMessage' => 'Fichier trop volumineux, 2MO maximum',
                        'mimeTypes' => ['image/jpg', 'image/jpeg', 'image/png', 'image/web'],
                        'mimeTypesMessage' => "formats autorisé:image/jpg','image/jpeg','image/png','image/web'"
                    ])
                ]
            ])
            ->add('stock')
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'title',
            ])
            ->add('unit', EntityType::class, [
                'class' => Unit::class,
                'choice_label' => 'name',
            ])
            ->add('Ajouter', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
