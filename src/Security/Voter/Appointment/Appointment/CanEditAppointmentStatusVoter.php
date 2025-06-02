<?php

namespace App\Security\Voter\Appointment\Appointment;

use App\Entity\Appointment\Appointment\Appointment;
use App\Services\Utils\Helper\LoggedUserService;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CanEditAppointmentVoter extends Voter
{
    public const CAN_EDIT_APPOINTMENT = 'CAN_EDIT_APPOINTMENT';

    public function __construct(
        private readonly LoggedUserService $loggedUserService
    ) {}

    protected function supports(string $attribute, $subject): bool
    {
        return $attribute === self::CAN_EDIT_APPOINTMENT;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        /** @var User $loggedUser */
        $loggedUser = $this->loggedUserService->getLoggedUser();

        if (!$loggedUser) {
            return false;
        }

        /** @var Appointment $appointment */
        $appointment = $subject;

        return $appointment->isScheduled();
    }
}
