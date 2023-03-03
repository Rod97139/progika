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
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
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
            ->add('adress')
            ->add('created_at')
            ->add('updated_at')
            ->add('user', EntityType::class, [
                'class' => User::class
            ])
                ->add('image', FileType::class, [
                    'label' => 'Votre image',
    
                    // unmapped means that this field is not associated to any entity property
                    'mapped' => false,
    
                    // make it optional so you don't have to re-upload the PDF file
                    // every time you edit the Product details
                    'required' => false,
    
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
                ->add('region', EntityType::class, [
                    'mapped' => false,
                    'class' => Region::class,
                    'placeholder' => 'Selectionner la région',
                    'required' => false
                    ]);
        
                   $builder->get('region')->addEventListener(
                        FormEvents::POST_SUBMIT,
                        function(FormEvent $event) {
                            $form = $event->getForm();
                            $this->addDepartementField($form->getParent(), $form->getData());
                        });
        
                    $builder->addEventListener(
                        FormEvents::POST_SET_DATA,
                        function(FormEvent $event) {
                            $data = $event->getData();
                            /* @var $city City */ 
                            $city = $data->getCity();
                            $form = $event->getForm();
        
                            if ($city) {
                                $departement = $city->getDepartement();
                                $region = $departement->getRegion();
                                $this->addDepartementField($form, $region);
                                $this->addCityField($form, $departement);
                                $form->get('region')->setdata($region);
                                $form->get('departement')->setdata($departement);
                            } else {
                                $this->addDepartementField($form, null);
                                $this->addCityField($form, null);
                            }
                        });
    }

    /**
     * Rajoute un champ departement au formulaire
     *
     * @param FormInterface $form
     * @param Region $region
     * @return void
     */
    private function addDepartementField(FormInterface $form, ?Region $region){

        $builder = $form->getConfig()->getFormFactory()->createNamedBuilder(
            'departement', 
            EntityType::class,
            null,
            [
                'mapped' => false,
                'class' => Departement::class,
                'placeholder' => $region ? 'Selectionner le département' : 'Selectionner une région',
                'required' => false,
                'auto_initialize' => false,
                'choices' => $region ? $region->getDepartements() : [],
                'disabled' => $region ? false : true
            ]
        );

        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function(FormEvent $event) {
                $form = $event->getForm();
                $this->addCityField($form->getParent(), $form->getData());
            }
        );
        $form->add($builder->getForm());
    }

    /**
     * Ajoute un champ city au formulaire
     *
     * @param FormInterface $form
     * @param Departement $departement
     * @return void
     */
    private function addCityField(FormInterface $form, ?Departement $departement) {
        $form->add('city', EntityType::class, [
            'class' => City::class,
            'placeholder' => $departement ? 'Selectionner la ville' : 'Selectionner un département',
            'choices' => $departement ? $departement->getCities() : [],
            'disabled' => $departement ? false : true
        ]);
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