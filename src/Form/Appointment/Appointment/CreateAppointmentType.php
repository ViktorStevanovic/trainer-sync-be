<?php

namespace App\Form\Appointment\Appointment;

use App\Entity\Client\Client;
use App\Entity\Trainer\Trainer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;

class CreateAppointmentType extends EditAppointmentType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('trainer', EntityType::class, [
                'class' => Trainer::class
            ])
            ->add('client', EntityType::class, [
                'class' => Client::class
            ]);
    }
}
