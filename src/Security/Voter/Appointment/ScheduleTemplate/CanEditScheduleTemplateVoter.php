<?php

namespace App\Security\Voter\Appointment\ScheduleTemplate;

use App\Entity\Appointment\ScheduleTemplate\ScheduleTemplate;
use App\Entity\Trainer\Trainer;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CanEditScheduleTemplateVoter extends Voter
{
    public const CAN_EDIT_SCHEDULE_TEMPLATE = 'CAN_EDIT_SCHEDULE_TEMPLATE';

    protected function supports(string $attribute, $subject): bool
    {
        // supports only the specific attribute and subject type
        return $attribute === self::CAN_EDIT_SCHEDULE_TEMPLATE && is_array($subject)
            && isset($subject['scheduleTemplate'], $subject['trainer'])
            && $subject['scheduleTemplate'] instanceof ScheduleTemplate
            && $subject['trainer'] instanceof Trainer;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        /** @var ScheduleTemplate $scheduleTemplate */
        $scheduleTemplate = $subject['scheduleTemplate'];
        /** @var Trainer $trainer */
        $trainer = $subject['trainer'];

        // check ownership
        return $scheduleTemplate->getTrainer() === $trainer;
    }
}
