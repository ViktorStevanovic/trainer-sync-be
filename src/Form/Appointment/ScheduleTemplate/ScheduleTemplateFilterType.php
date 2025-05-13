<?php

namespace App\Form\Appointment\ScheduleTemplate;

use App\Enum\Appointment\ScheduleTemplate\WeekDaysEnum;
use App\Model\Form\Appointment\ScheduleTemplate\ScheduleTemplateFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ScheduleTemplateFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('weekDays', ChoiceType::class, [
                'choices' => WeekDaysEnum::getChoices(),
                'multiple' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'data_class' => ScheduleTemplateFilter::class,
                'allow_extra_fields' => false
            ]);
    }
}
