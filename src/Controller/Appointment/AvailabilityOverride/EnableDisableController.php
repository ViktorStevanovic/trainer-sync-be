<?php

namespace App\Controller\Appointment\AvailabilityOverride;

use App\Controller\Controller;
use App\Entity\Appointment\AvailabilityOverride\AvailabilityOverride;
use App\Error\ErrorCodeEnum;
use App\Security\Voter\Appointment\AvailabilityOverride\CanEnableDisableAvailabilityOverrideVoter;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Throwable;

class EnableDisableController extends Controller
{
    #[Route(
        path: '/availability-override/{availabilityOverride}/{enableDisable}',
        requirements: ['availabilityOverride' => '\d+', 'enableDisable' => 'enable|disable'],
        methods: ['PUT']
    )]
    #[IsGranted(
        attribute: CanEnableDisableAvailabilityOverrideVoter::CAN_ENABLE_DISABLE_AVAILABILITY_OVERRIDE,
        subject: ['availabilityOverride', 'enableDisable'],
        message: ErrorCodeEnum::ERROR_ENTITY_001
    )]
    public function enableDisableAvailabilityOverride(AvailabilityOverride $availabilityOverride, string $enableDisable): JsonResponse
    {
        $this->beginTransaction();

        try {
            $wasActive = $availabilityOverride->isActive();

            if ($wasActive && $enableDisable === 'disable') {
                $availabilityOverride->setActive(false);
            }

            if (!$wasActive && $enableDisable === 'enable') {
                $availabilityOverride->setActive(true);
            }

            $this->save();
            $this->commit();
        } catch (Throwable $e) {
            $this->rollback();
            throw new Exception(message: $e->getMessage());
        }

        return $this->renderEmptyResponse();
    }
}
