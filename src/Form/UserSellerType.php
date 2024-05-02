<?php

namespace App\Form;

use App\Entity\Label;
use App\Entity\Region;
use App\Entity\User;
use App\Entity\UserSeller;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class UserSellerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('companyName', TextType::class, [
                'label' => 'Nom de votre entreprise',
    ])
            ->add('phone', null, [
                'constraints' => [
                    new Regex([
                        'pattern' => '/^0[1-9](\d{2}){4}$/',
                        'message' => 'Veuillez saisir un numéro de téléphone valide.'
                    ])
                    ],
                    'label' => 'Numéro de téléphone',

            ])           
            ->add('description', TextType::class, [

                'label' => 'Description de votre entreprise',
    ])
            ->add('region', EntityType::class, [
                'class' => Region::class,
                'choice_label' => 'name',
                'label' => 'Choisissez votre région',

            ])
            ->add('labels', EntityType::class, [
                'class' => Label::class,
                'choice_label' => 'title',
                'multiple' => true,
                'expanded' => true,  // Utiliser des checkboxes au lieu d'une liste déroulante
                'by_reference' => false,  // Pour traiter correctement les modifications
                'label' => 'Cochez les labels que vous possédez :',
            ])

        
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserSeller::class,
        ]);
    }
}
