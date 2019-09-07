<?php

namespace App\Form;

use App\Entity\Ticket;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;

class TicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, ['label'=>'Prénom', 
                'attr' => [
                    'placeholder'=> "Prénom"
                    ]
                    ])
            ->add('lastName', TextType::class, ['label'=>'Nom', 
                'attr' => [
              'placeholder'=> "Nom" 
                    ]   
                ])
            ->add('country', CountryType::class, [     
                'label'=> "Pays"
                ])
            ->add('dateBirth', BirthdayType::class, [     
                'label'=> "Date de naissance"
                ]
             )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ticket::class,
        ]);
    }
}
