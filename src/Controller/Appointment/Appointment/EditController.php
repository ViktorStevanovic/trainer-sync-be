<?php

namespace App\Controller\Appointment\Appointment;

use App\Controller\Controller;
use App\Entity\Appointment\Appointment\Appointment;
use App\Error\ErrorCodeEnum;
use App\Security\Voter\Appointment\Appointment\CanEditAppointmentVoter;
use App\Services\Appointment\Appointment\AppointmentEditManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class EditController extends Controller
{
    public function __construct(
        private readonly AppointmentEditManager $appointmentManager
    ) {}

    #[Route(path: '/appointment/{appointment}', requirements: ['appointment' => 'd\+'], methods: ['PUT'])]
    #[IsGranted(
        attribute: CanEditAppointmentVoter::CAN_EDIT_APPOINTMENT,
        subject: 'appointment',
        message: ErrorCodeEnum::ERROR_ENTITY_001
    )]
    public function editAppointment(Request $request, Appointment $appointment): JsonResponse
    {
        $this->beginTransaction();
        try {
            $this->appointmentManager->manageAppointmentEdit($appointment, $request);
            $this->commit();
        } catch (\Throwable $th) {
            $this->rollback();
            throw $th;
        }
        return $this->renderEmptyResponse();
    }

    #[Route(path: '/appointment/{appointment}/{completeCancel}', requirements: ['appointment' => 'd\+', 'completeCancel' => 'complete|cancel'], methods: ['PUT'])]
    #[IsGranted(
        attribute: CanEditAppointmentVoter::CAN_EDIT_APPOINTMENT,
        subject: 'appointment',
        message: ErrorCodeEnum::ERROR_ENTITY_001
    )]
    public function editAppointmentStatus(Appointment $appointment, string $completeCancel): JsonResponse
    {
        $this->beginTransaction();
        try {
            $this->appointmentManager->manageAppointmentStatus($appointment, $completeCancel);
            $this->commit();
        } catch (\Throwable $th) {
            $this->rollback();
            throw $th;
        }
        return $this->renderEmptyResponse();
    }
}
