<?php

namespace App\Controller\Appointment\Appointment;

use App\Controller\Controller;
use App\Entity\Appointment\Appointment\Appointment;
use App\Error\ErrorCodeEnum;
use App\Security\Voter\Appointment\Appointment\CanViewAppointmentVoter;
use App\Serializer\Appointment\Appointment\AppointmentGroupsHelper;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DetailController extends Controller
{
    #[Route(path: '/appointment/{appointment}', requirements: ['appointment' => '\d+'], methods: ['GET'])]
    #[IsGranted(
        attribute: CanViewAppointmentVoter::CAN_VIEW_APPOINTMENT,
        subject: 'appointment',
        message: ErrorCodeEnum::ERROR_ENTITY_001
    )]
    public function detailAppointment(Appointment $appointment): JsonResponse
    {
        return $this->renderSerializedData($appointment, AppointmentGroupsHelper::appointment());
    }
}
