<?php

namespace App\Security\Voter\Appointment\Appointment;

use App\Entity\Appointment\Appointment\Appointment;
use App\Entity\User\User;
use App\Services\Appointment\Appointment\AppointmentChecker;
use App\Services\Utils\Helper\LoggedUserService;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class CanViewAppointmentVoter extends Voter
{
    public const CAN_VIEW_APPOINTMENT = 'CAN_VIEW_APPOINTMENT';

    public function __construct(
        private readonly LoggedUserService $loggedUserService,
        private readonly AppointmentChecker $appointmentChecker
    ) {}

    protected function supports(string $attribute, $subject): bool
    {
        return $attribute === self::CAN_VIEW_APPOINTMENT;
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

        /** @var Appointment $appointment */
        $appointment = $subject;

        return $this->appointmentChecker->checkVisibleAppointment(appointment: $appointment, user: $loggedUser);
    }
}
