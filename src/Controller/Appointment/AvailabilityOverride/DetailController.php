<?php

namespace App\Controller\Appointment\AvailabilityOverride;

use App\Controller\Controller;
use App\Entity\Appointment\AvailabilityOverride\AvailabilityOverride;
use App\Error\ErrorCodeEnum;
use App\Security\Voter\Appointment\AvailabilityOverride\CanViewAvailabilityOverrideVoter;
use App\Serializer\Appointment\AvailabilityOverride\AvailabilityOverrideGroupsHelper;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DetailController extends Controller
{
    #[Route(path: '/availability-override/{availabilityOverride}', requirements: ['availabilityOverride' => '\d+'], methods: ['GET'])]
    #[IsGranted(
        attribute: CanViewAvailabilityOverrideVoter::CAN_VIEW_AVAILABILITY_OVERRIDE,
        subject: 'availabilityOverride',
        message: ErrorCodeEnum::ERROR_ENTITY_001
    )]
    public function detailScheduleTemplate(AvailabilityOverride $availabilityOverride): JsonResponse
    {
        return $this->renderSerializedData($availabilityOverride, AvailabilityOverrideGroupsHelper::availabilityOverride());
    }
}
