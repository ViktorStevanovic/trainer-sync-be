<?php

namespace App\Form\Appointment\Appointment;

use App\Entity\Appointment\Appointment\Appointment;
use App\Entity\Appointment\AvailabilitySlot\AvailabilitySlot;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditAppointmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('availabilitySlot', EntityType::class, [
                'class' => AvailabilitySlot::class
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
