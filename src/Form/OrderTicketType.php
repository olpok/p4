<?php

namespace App\Form;

use App\Entity\Ticket;
use App\Form\TicketType;
use App\Entity\OrderTicket;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class OrderTicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class)
            ->add('price', TextType::class, array('attr'=>array('readonly'=>'readonly')))
            ->add(
                'tickets', CollectionType::class, array(
                            'entry_type' => TicketType::class,
                            'entry_options' => array('label' =>false),
                            'label'=>false,
                            'required'=>false,
                            'allow_add'=>false,
                            'allow_delete'=>false,
                            'prototype'=>true,
                            'by_reference'=>true,
                            'mapped'=>true,
                           )
                );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OrderTicket::class,
        ]);
    }
}
