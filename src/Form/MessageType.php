<?php

namespace App\Form;

use App\Entity\Conversation;
use App\Entity\Message;
use App\Entity\User;
use DateTime;
use Doctrine\DBAL\Types\DateTimeImmutableType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextType::class, [
                'attr' => ['autocomplete' => 'off',
                'autofocus' => 'autofocus',
                // 'class' => 'overflow-auto'
                ]
                ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                //    'formnovalidate' => 'formnovalidate',
                    // 'class' => 'overflow-auto'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
        ]);
    }
}
