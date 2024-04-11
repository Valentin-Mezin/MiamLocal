<?php

namespace App\Form;

use App\Entity\Label;
use App\Entity\Region;
use App\Entity\User;
use App\Entity\UserSeller;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserSellerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('companyName')
            ->add('phone')
            ->add('description')

            ->add('createdAt', null, [
                'widget' => 'single_text',
            ])
            ->add('UpdatedAt', null, [
                'widget' => 'single_text',
            ])
            ->add('region', EntityType::class, [
                'class' => Region::class,
                'choice_label' => 'name',
            ])
            ->add('labels', EntityType::class, [
                'class' => Label::class,
                'choice_label' => 'title',
                'multiple' => true,
                'expanded' => true,  // Utiliser des checkboxes au lieu d'une liste déroulante
                'by_reference' => false,  // Pour traiter correctement les modifications
            ])
            // ->add('user', EntityType::class, [
            //     'class' => User::class,
            //     'choice_label' => 'id',
            // ])

            ->add('submit', SubmitType::class, [
                'label' => 'Valider'
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
