<?php

namespace App\Security\Voter\Appointment\ScheduleTemplate;

use App\Entity\Appointment\ScheduleTemplate\ScheduleTemplate;
use App\Entity\User\User;
use App\Services\Utils\Helper\LoggedUserService;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class CanViewScheduleTemplateVoter extends Voter
{
    public const CAN_VIEW_SCHEDULE_TEMPLATE = 'CAN_VIEW_SCHEDULE_TEMPLATE';

    public function __construct(
        private readonly LoggedUserService $loggedUserService
    ) {}

    protected function supports(string $attribute, $subject): bool
    {
        return $attribute === self::CAN_VIEW_SCHEDULE_TEMPLATE;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        /** @var User $loggedUser */
        $loggedUser = $this->loggedUserService->getLoggedUser();

        if (!$loggedUser) {
            return false;
        }

        if ($loggedUser->isAdmin()) {
            return true;
        }

        /** @var ScheduleTemplate $scheduleTemplate */
        $scheduleTemplate = $subject;

        return $scheduleTemplate->getTrainer() === $loggedUser->getTrainer();
    }
}
