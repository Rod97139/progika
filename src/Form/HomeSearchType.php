<?php

namespace App\Form;

use App\Entity\Lodging;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HomeSearchType extends AbstractType
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
            ->add('adress')
            ->add('image')
            ->add('created_at')
            ->add('updated_at')
            ->add('city')
            ->add('user')
            ->add('criteria')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lodging::class,
        ]);
    }
}
