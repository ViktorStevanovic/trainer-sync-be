<?php

namespace App\Form\Appointment\Appointment;

use App\Entity\Appointment\Appointment\Appointment;
use App\Entity\Appointment\AvailabilitySlot\AvailabilitySlot;
use App\Entity\Client\Client;
use App\Entity\Trainer\Trainer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppointmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('slot', EntityType::class, [
                'class' => AvailabilitySlot::class
            ])
            ->add('trainer', EntityType::class, [
                'class' => Trainer::class
            ])
            ->add('client', EntityType::class, [
                'class' => Client::class
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'data_class' => Appointment::class,
                'allow_extra_fields' => false
            ]);
    }
}
