<?php

namespace App\Form\Appointment\ScheduleTemplate;

use App\Entity\Appointment\ScheduleTemplate\ScheduleTemplate;
use App\Enum\Appointment\ScheduleTemplate\WeekDaysEnum;
use App\Services\Utils\Helper\DoctrineHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ScheduleTemplateType extends AbstractType
{
    public function __construct(
        private readonly DoctrineHelper $doctrineHelper
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('weekDay', ChoiceType::class, [
                // TODO capire come far si che io possa passare monday e non 1
                'choices' => WeekDaysEnum::getChoices(),
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
            ->add('blockTime', IntegerType::class) //TODO validazione blocchi da 15 minuti
            ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                $form = $event->getForm();

                /** @var ScheduleTemplate $scheduleTemplate */
                $scheduleTemplate = $event->getData();

                $trainer = $scheduleTemplate->getTrainer();
                if (!$trainer) {
                    // optional: add error if trainer is required
                    $form->addError(new FormError('Trainer is required.'));
                    return;
                }

                $startTime = $scheduleTemplate->getStartTime();
                $endTime = $scheduleTemplate->getEndTime();

                if ($startTime >= $endTime) {
                    $form->addError(new FormError('Start time must be before end time.'));
                    return;
                }

                /** @var ScheduleTemplate[] $existingTemplates */
                $existingTemplates = $this->doctrineHelper->getRepository(ScheduleTemplate::class)->findBy([
                    'trainer' => $trainer,
                    'weekDay' => $scheduleTemplate->getWeekDay(),
                ]);

                foreach ($existingTemplates as $existingTemplate) {
                    // skip self when editing
                    if ($scheduleTemplate->getId() && $existingTemplate->getId() === $scheduleTemplate->getId()) {
                        continue;
                    }

                    $existingStart = $existingTemplate->getStartTime();
                    $existingEnd = $existingTemplate->getEndTime();

                    // Check if time intervals overlap
                    if (
                        ($startTime < $existingEnd) && ($endTime > $existingStart)
                    ) {
                        $form->addError(new FormError(sprintf(
                            'Schedule conflict: overlaps with another schedule from %s to %s.',
                            $existingStart->format('H:i'),
                            $existingEnd->format('H:i')
                        )));
                        return;
                    }
                }
            });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'data_class' => ScheduleTemplate::class,
                'allow_extra_fields' => true
            ]);
    }
}
