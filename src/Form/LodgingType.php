<?php

namespace App\Form;

use App\Entity\Criteria;
use App\Entity\Departement;
use App\Entity\Lodging;
use App\Entity\Region;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LodgingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('number_rooms')
            ->add('max_people')
            ->add('surface')
            ->add('weekly_base_price')
            ->add('created_at')
            ->add('updated_at')
            ->add('type')

            ->add('region', EntityType::class, [
            'mapped' => false,
            'class' => Region::class,
            'choice_label' => 'name',
            'placeholder' => 'Choisir Région'
            ])

            ->add('departement', EntityType::class, [
            'mapped' => false,
            'class' => Departement::class,
            'choice_label' => 'name',
            'placeholder' => 'Choisir Département'
            ]) 
            
            ->add('city')
            ->add('adress')
            ->add('lodging_long')
            ->add('lodging_lat')
            ->add('criteria')       ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lodging::class,
        ]);
    }
}
