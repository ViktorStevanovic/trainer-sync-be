<?php

namespace App\Form\Appointment\Appointment\Association;

use App\Form\Appointment\Appointment\CreateAppointmentType;
use Symfony\Component\Form\FormBuilderInterface;

class TrainerAppointmentType extends CreateAppointmentType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        $builder
            ->remove('trainer');
    }
}
