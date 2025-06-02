<?php

namespace App\Form\Appointment\Appointment;

use App\Entity\Client\Client;
use App\Entity\Trainer\Trainer;
use App\Enum\Appointment\Appointment\AppointmentStatusEnum;
use App\Model\Form\Appointment\Appointment\AppointmentFilter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppointmentFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-M-d',
                'html5' => false
            ])
            ->add('trainer', EntityType::class, [
                'class' => Trainer::class
            ])
            ->add('client', EntityType::class, [
                'class' => Client::class
            ])
            ->add('status', ChoiceType::class, [
                'choices' => AppointmentStatusEnum::getValidStatuses()
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'data_class' => AppointmentFilter::class,
                'allow_extra_fields' => false
            ]);
    }
}
