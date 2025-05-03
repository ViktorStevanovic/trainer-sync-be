<?php

namespace App\Security\Voter\Appointment\ScheduleTemplate;

use App\Entity\Trainer\Trainer;
use App\Entity\User\User;
use App\Enum\User\RoleEnum;
use App\Services\Utils\Helper\LoggedUserService;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class CanViewScheduleTemplateVoter extends Voter
{
    public const CAN_VIEW_TRAINER_SCHEDULE_TEMPLATES = 'CAN_CAN_VIEW_TRAINER_SCHEDULE_TEMPLATES';

    public function __construct(
        private readonly LoggedUserService $loggedUserService
    ) {}

    protected function supports(string $attribute, $subject): bool
    {
        return $attribute === self::CAN_VIEW_TRAINER_SCHEDULE_TEMPLATES && $subject instanceof Trainer;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        /** @var User $loggedUser */
        $loggedUser = $this->loggedUserService->getLoggedUser();

        if (!$loggedUser) {
            return false;
        }

        /** @var Trainer $trainer */
        $trainer = $subject;

        if ($loggedUser->isAdmin()) {
            return true;
        }

        return $loggedUser === $trainer->getUser();
    }
}
