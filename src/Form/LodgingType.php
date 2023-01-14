<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Criteria;
use App\Entity\Departement;
use App\Entity\Lodging;
use App\Entity\Region;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
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
            'placeholder' => 'Régions'
            ])

            // ->add('departement', ChoiceType::class, [
            // 'mapped' => false,
            // 'placeholder' => 'Départements',
            // 'required' => false
            // ]) 
            ->add('departement', EntityType::class, [
                'mapped' => false,
                'class' => Departement::class,
                'choice_label' => 'name',
                'placeholder' => 'Departement'
                ])
            
            ->add('city', ChoiceType::class, [
                'placeholder' => 'Ville (Choisir une Région et un Département)'
                ]) 

            ->add('adress')
            ->add('lodging_long')
            ->add('lodging_lat')
            ->add('criteria', EntityType::class, [
                'multiple' => true,
                'class' => Criteria::class,
                'choice_label' => 'name',
                'group_by' => 'type',
                'placeholder' => 'Choisir équipement',
                'attr' => [
                    'class' => 'select2']
                ])      
            
            ->add('Valider', SubmitType::class)
                ;

            // $formModifier = function(FormInterface $form, Region $region = null){
            //     $departements = (($region === null) ? [] : $region->getDepartements());
               
                

            //     $form->add('departement', EntityType::class, [
            //         'mapped' => false,
            //         'class' => Departement::class,
            //         'choices' => $departements,
            //         'choice_label' => 'name',
            //         'placeholder' => 'Départements',
            //         'label' => 'Départements',
            //         'required' => false
            //     ]);
                
            // };

            // $builder->get('region')->addEventListener(
            //     FormEvents::POST_SUBMIT,
            //     function(FormEvent $event) use($formModifier) {
            //         $region = $event->getForm()->getData();
                    
            //         $formModifier($event->getForm()->getParent(), $region);

            //     });


            $formModifier2 = function(FormInterface $form, Departement $departement = null){
                    
               
                $cities = (($departement === null) ? [] : $departement->getCities());
               

                $form->add('city', EntityType::class, [
                    'class' => City::class,
                    'choices' => $cities,
                    'choice_label' => 'name',
                    'placeholder' => 'Villes',
                    'label' => 'Villes',
                    'required' => false
                ]);
            };


            
            $builder->get('departement')->addEventListener(
                FormEvents::POST_SUBMIT,
                function(FormEvent $event2) use($formModifier2) {
                    
                    
                    $departement = $event2->getForm()->getData();
                    $formModifier2($event2->getForm()->getParent(), $departement);

                });


              


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lodging::class,
        ]);
    }
}
