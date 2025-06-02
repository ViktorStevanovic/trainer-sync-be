<?php

namespace App\Controller\Appointment\Appointment;

use App\Controller\Controller;
use App\Form\Appointment\Appointment\AppointmentFilterType;
use App\Model\Form\Appointment\Appointment\AppointmentFilter;
use App\Serializer\Appointment\Appointment\AppointmentGroupsHelper;
use App\Services\Appointment\Appointment\AppointmentLister;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ListController extends Controller
{
    public function __construct(
        private readonly AppointmentLister $appointmentLister,
    ) {}

    #[Route(path: '/appointment', methods: ['GET'])]
    public function listAppointments(Request $request): JsonResponse
    {
        $filter = new AppointmentFilter();
        $form = $this->createForm(AppointmentFilterType::class, $filter);

        $form->submit($request->query->all());
        if (!$form->isValid()) {
            return $this->renderSerializedFormErrors($form);
        }

        $appointments = $this->appointmentLister->getVisibleAppointments(filter: $filter);
        return $this->renderSerializedData($appointments, AppointmentGroupsHelper::appointment());
    }
}
