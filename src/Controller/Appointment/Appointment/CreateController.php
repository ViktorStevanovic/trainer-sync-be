<?php

namespace App\Controller\Appointment\Appointment;

use App\Controller\Controller;
use App\Entity\Appointment\Appointment\Appointment;
use App\Services\Appointment\Appointment\AppointmentCreateManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CreateController extends Controller
{
    public function __construct(
        private readonly AppointmentCreateManager $appointmentManager
    ) {}

    #[Route(path: '/appointment', methods: ['POST'])]
    public function createAppointment(Request $request): JsonResponse
    {
        $this->beginTransaction();
        try {
            $appointment = new Appointment();
            $this->appointmentManager->manageAppointmentCreation($appointment, $request);
            $this->commit();
        } catch (\Throwable $th) {
            $this->rollback();
            throw $th;
        }
        return $this->renderEmptyResponse();
    }
}
