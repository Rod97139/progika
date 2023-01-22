<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Criteria;
use App\Entity\Departement;
use App\Entity\Lodging;
use App\Entity\Region;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class LodgingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $routeName = $options['routeName'];
        $builder
            ->add('name')
            ->add('description')
            ->add('number_rooms')
            ->add('max_people')
            ->add('surface')
            ->add('criteria', EntityType::class, [
                'multiple' => true,
                'class' => Criteria::class,
                'choice_label' => 'name',
                'group_by' => 'type',
                'placeholder' => 'Choisir équipement',
                'attr' => [
                    'class' => 'select2']
                ])      
            ->add('weekly_base_price')
            

            // ->add('region', EntityType::class, [
            // 'mapped' => false,
            // 'class' => Region::class,
            // 'choice_label' => 'name',
            // 'placeholder' => 'Régions'
            // ])

            // ->add('departement', ChoiceType::class, [
            // 'mapped' => false,
            // 'placeholder' => 'Départements',
            // 'required' => false,
            // 'attr' => [
            //     'disabled' => true
            // ]
            // ]) ;

            ->add('departement', EntityType::class, [
                'mapped' => false,
                'class' => Departement::class,
                'choice_label' => 'name',
                'placeholder' => 'Departement'
            ]);

            
               
                    if ($routeName === "app_lodging_edit") {
                $builder->add('city', EntityType::class, [
                        'class' => City::class,
                        'choice_label' => 'name',
                        'placeholder' => 'Villes',
                        'label' => 'Ville',
                        'attr' => [
                            'class' => 'select2'
                        ]
                    ]); 
                } else {
                    $builder->add('city', ChoiceType::class, [
                        'placeholder' => 'Ville (Choisir un Département)',
                        'required' => false,
                        'disabled' => true
                    ]) ;
                }

            $builder->add('adress')
           
            ->add('created_at')
            ->add('updated_at')
            ->add('user', EntityType::class, [
                'class' => User::class
            ])
                // ...
                ->add('image', FileType::class, [
                    'label' => 'Votre image',
    
                    // unmapped means that this field is not associated to any entity property
                    'mapped' => false,
    
                    // make it optional so you don't have to re-upload the PDF file
                    // every time you edit the Product details
                    'required' => true,
    
                    // unmapped fields can't define their validation using annotations
                    // in the associated entity, so you can use the PHP constraint classes
                    'constraints' => [
                        new File([
                            'maxSize' => '1024k',
                            'mimeTypes' => [
                                'image/jpg',
                                'image/png',
                                'image/jpeg',
                            ],
                            'mimeTypesMessage' => 'Please upload a valid Image',
                        ])
                    ],
                ])
                // ...
                ->add('Valider', SubmitType::class)
                ;
                
                
        dump($routeName);

        if ($routeName !== "app_lodging_edit") {
            $formModifier2 = function(FormInterface $form, Departement $departement = null){
                $cities = (($departement === null) ? [] : $departement->getCities());
                $form->add('city', EntityType::class, [
                    'class' => City::class,
                    'choices' => $cities,
                    'choice_label' => 'name',
                    'placeholder' => 'Ville',
                    'label' => 'Ville',
                    'required' => true
                ]);
            };
            
            $builder->get('departement')->addEventListener(
                FormEvents::POST_SUBMIT,
                function(FormEvent $event2) use($formModifier2) {
                    
                    $departement = $event2->getForm()->getData();
                    $formModifier2($event2->getForm()->getParent(), $departement);

            });
        }

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

        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lodging::class,
        ]);
        $resolver->setRequired([
            'routeName',
        ]);
    }
}
