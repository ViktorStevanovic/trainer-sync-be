<?php

namespace App\Form\Appointment\Appointment;

use Symfony\Component\Form\FormBuilderInterface;

class ClientAppointmentType extends AppointmentType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        $builder
            ->remove('client');
    }
}
