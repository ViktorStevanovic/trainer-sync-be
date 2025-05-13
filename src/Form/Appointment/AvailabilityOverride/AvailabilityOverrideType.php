<?php

namespace App\Form\Appointment\AvailabilityOverride;

use App\Entity\Appointment\AvailabilityOverride\AvailabilityOverride;
use App\Services\Utils\Helper\DoctrineHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AvailabilityOverrideType extends AbstractType
{
    public function __construct(
        private readonly DoctrineHelper $doctrineHelper
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('fullDayOverride', CheckboxType::class)
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
            ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                $form = $event->getForm();

                /** @var AvailabilityOverride $override */
                $override = $event->getData();

                $repo = $this->doctrineHelper->getRepository(AvailabilityOverride::class);

                $existingOverrides = $repo->findBy([
                    'trainer' => $override->getTrainer(),
                    'date' => $override->getDate(),
                ]);

                // Check for full day conflicts
                foreach ($existingOverrides as $existing) {
                    if ($existing->isFullDayOverride()) {
                        $form->addError(new FormError('A full day override already exists for this date.'));
                        return;
                    }
                }

                // If i'm creating a full day override, deactivate all the others
                if ($override->isFullDayOverride()) {
                    foreach ($existingOverrides as $existing) {
                        $existing->setActive(false);
                    }
                    return;
                }

                if (!$override->getStartTime() || !$override->getEndTime()) {
                    $form->addError(new FormError('Start and end time are required for partial overrides.'));
                    return;
                }

                if ($override->getStartTime() >= $override->getEndTime()) {
                    $form->addError(new FormError('Start time must be before end time.'));
                    return;
                }

                foreach ($existingOverrides as $existing) {
                    // Skip the current override (if editing)
                    if ($existing->getId() === $override->getId()) {
                        continue;
                    }
                    if (
                        $override->getStartTime() < $existing->getEndTime() &&
                        $override->getEndTime() > $existing->getStartTime()
                    ) {
                        $form->addError(new FormError('This time slot overlaps with another override.'));
                        return;
                    }
                }
            });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'data_class' => AvailabilityOverride::class,
                'allow_extra_fields' => false
            ]);
    }
}
