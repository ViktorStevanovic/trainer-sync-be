<?php

namespace App\Controller\Appointment\Appointment;

use App\Controller\Controller;
use App\Entity\Appointment\Appointment\Appointment;
use App\Entity\Appointment\AvailabilityOverride\AvailabilityOverride;
use App\Error\ErrorCodeEnum;
use App\Form\Appointment\AvailabilityOverride\AvailabilityOverrideType;
use App\Security\Voter\Appointment\AvailabilityOverride\CanViewAvailabilityOverrideVoter;
use App\Services\Appointment\Appointment\AppointmentManager;
use App\Services\Appointment\AvailabilityOverride\AvailabilityOverrideManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CreateEditController extends Controller
{
    public function __construct(
        private readonly AppointmentManager $appointmentManager
    ) {}

    #[Route(path: '/appointment', methods: ['POST'])]
    public function createAppointment(Request $request): JsonResponse
    {
        return $this->manageAppointment($request, new Appointment());
    }

    #[Route(path: '/appointment/{appointment}', requirements: ['appointment' => 'd\+'], methods: ['POST'])]
    public function editAppointment(Request $request, Appointment $appointment): JsonResponse
    {
        return $this->manageAppointment($request, $appointment);
    }

    /**
     * @param Request $request
     * @param Appointment $appointment
     * 
     * @return JsonResponse
     */
    private function manageAppointment(Request $request, Appointment $appointment): JsonResponse
    {
        $this->beginTransaction();
        try {
            $this->appointmentManager->manageAppointment($appointment, $request);
            $this->commit();
        } catch (\Throwable $th) {
            $this->rollback();
            throw $th;
        }
        return $this->renderEmptyResponse();
    }
}
