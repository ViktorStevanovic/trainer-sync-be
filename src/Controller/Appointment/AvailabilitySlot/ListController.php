<?php

namespace App\Controller\Appointment\AvailabilitySlot;

use App\Controller\Controller;
use App\Entity\Appointment\AvailabilitySlot\AvailabilitySlot;
use App\Form\Appointment\AvailabilitySlot\AvailabilitySlotFilterType;
use App\Model\Form\Appointment\AvailabilitySlot\AvailabilitySlotFilter;
use App\Serializer\Appointment\Appointment\AppointmentGroupsHelper;
use App\Serializer\Appointment\AvailabilitySlot\AvailabilitySlotGroupsHelper;
use App\Services\Appointment\AvailabilitySlot\AvailabilitySlotLister;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ListController extends Controller
{
    public function __construct(
        private readonly AvailabilitySlotLister $slotLister
    ) {}

    #[Route(path: '/availability-slot', methods: ['GET'])]
    public function listTrainersAvailabilitySlots(Request $request): JsonResponse
    {
        $filter = new AvailabilitySlotFilter();
        $form = $this->createForm(AvailabilitySlotFilterType::class, $filter);
        dump($filter);
        dump($request->query->all());
        $form->submit($request->query->all());
        if (!$form->isValid()) {
            return $this->renderSerializedFormErrors($form);
        }
        dump($filter);
        $slots = $this->slotLister->getTrainersAvailabilitySlots(filter: $filter);
        return $this->renderSerializedData($slots, AvailabilitySlotGroupsHelper::availabilitySlot());
    }


    #[Route(path: '/availability-slot/conflict', methods: ['GET'])]
    public function listBookedAppointmentsConfilct(Request $request): JsonResponse
    {
        $filter = new AvailabilitySlotFilter()->setBooked(true);
        $form = $this->createForm(AvailabilitySlotFilterType::class, $filter);

        $form->submit($request->query->all());
        if (!$form->isValid()) {
            return $this->renderSerializedFormErrors($form);
        }

        $slots = $this->slotLister->getTrainersAvailabilitySlots(filter: $filter);
        // Recupera solo gli appuntamenti prenotati
        $appointments = array_filter(
            array_map(
                fn(AvailabilitySlot $slot) => $slot->getScheduledAppointment(),
                $slots
            )
        );

        return $this->renderSerializedData($appointments, AppointmentGroupsHelper::appointment());
    }
}
