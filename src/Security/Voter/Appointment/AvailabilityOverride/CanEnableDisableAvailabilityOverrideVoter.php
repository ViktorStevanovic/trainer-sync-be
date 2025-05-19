<?php

namespace App\Security\Voter\Appointment\AvailabilityOverride;

use App\Entity\Appointment\AvailabilityOverride\AvailabilityOverride;
use App\Entity\User\User;
use App\Services\Utils\Helper\LoggedUserService;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class CanEnableDisableAvailabilityOverrideVoter extends Voter
{
    public const CAN_ENABLE_DISABLE_AVAILABILITY_OVERRIDE = 'CAN_ENABLE_DISABLE_AVAILABILITY_OVERRIDE';

    public function __construct(
        private readonly LoggedUserService $loggedUserService
    ) {}

    protected function supports(string $attribute, $subject): bool
    {
        return $attribute === self::CAN_ENABLE_DISABLE_AVAILABILITY_OVERRIDE;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        /** @var User $loggedUser */
        $loggedUser = $this->loggedUserService->getLoggedUser();

        if (!$loggedUser) {
            return false;
        }

        /** @var AvailabilityOverride $availabilityOverride */
        $availabilityOverride = $subject['availabilityOverride'];

        $enableDisable = $subject['enableDisable'];

        if (
            ($availabilityOverride->isActive() && $enableDisable === 'disable') ||
            (!$availabilityOverride->isActive() && $enableDisable === 'enable')
        ) {
            return true;
        }

        return false;
    }
}
