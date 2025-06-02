<?php

namespace App\Form\Appointment\AvailabilitySlot;

use App\Entity\Trainer\Trainer;
use App\Model\Form\Appointment\AvailabilitySlot\AvailabilitySlotFilter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AvailabilitySlotFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('trainer', EntityType::class, [
                'class' => Trainer::class
            ])
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-M-d',
                'html5' => false
            ])
            ->add('startTime', TimeType::class, [
                'input'  => 'datetime',
                'widget' => 'single_text',
                'with_seconds' => false,
            ])
            ->add('endTime', TimeType::class, [
                'input'  => 'datetime',
                'widget' => 'single_text',
                'with_seconds' => false,
            ])
            ->add('status', CheckboxType::class)
            ->add('booked', CheckboxType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'data_class' => AvailabilitySlotFilter::class,
                'allow_extra_fields' => false
            ]);
    }
}
