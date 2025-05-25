<?php

namespace App\Services\Appointment\AvailabilityOverride;

use App\Entity\Appointment\AvailabilityOverride\AvailabilityOverride;
use App\Entity\Appointment\AvailabilitySlot\AvailabilitySlot;
use App\Enum\Appointment\Appointment\AppointmentStatusEnum;
use App\Services\Appointment\AvailabilitySlot\AvailabilitySlotLister;
use App\Services\Utils\Helper\DoctrineHelper;

class AvailabilityOverrideManager
{
    public function __construct(
        private readonly DoctrineHelper $doctrineHelper,
        private readonly AvailabilitySlotLister $availabilitySlotLister
    ) {}

    /**
     * Crea un nuovo override disattivando gli slot coinvolti
     */
    public function createAvailabilityOverride(AvailabilityOverride $availabilityOverride): void
    {
        $slots = $this->availabilitySlotLister->getSlotsFromAvailabilityOverride($availabilityOverride);

        foreach ($slots as $slot) {
            $this->deactivateSlot($slot);
        }

        $this->doctrineHelper->save($availabilityOverride);
    }

    /**
     * Modifica un override: riattiva gli slot del vecchio e disattiva quelli del nuovo
     */
    public function editAvailabilityOverride(AvailabilityOverride $oldOverride, AvailabilityOverride $newOverride): void
    {
        // Riattiva slot del vecchio override
        $oldSlots = $this->availabilitySlotLister->getSlotsFromAvailabilityOverride($oldOverride);
        foreach ($oldSlots as $slot) {
            $this->activateSlot($slot);
        }

        // Disattiva slot del nuovo override
        $newSlots = $this->availabilitySlotLister->getSlotsFromAvailabilityOverride($newOverride);
        foreach ($newSlots as $slot) {
            $this->deactivateSlot($slot);
        }

        $this->doctrineHelper->save($newOverride);
    }

    /**
     * Disattiva un override e riattiva tutti gli slot coinvolti
     */
    public function deactivateAvailabilityOverride(AvailabilityOverride $availabilityOverride): void
    {
        $slots = $this->availabilitySlotLister->getSlotsFromAvailabilityOverride($availabilityOverride);

        foreach ($slots as $slot) {
            $this->activateSlot($slot);
        }

        $availabilityOverride->setActive(false);
        $this->doctrineHelper->save($availabilityOverride);
    }

    /**
     * Riattiva un override e disattiva tutti gli slot coinvolti
     */
    public function activateAvailabilityOverride(AvailabilityOverride $availabilityOverride): void
    {
        $slots = $this->availabilitySlotLister->getSlotsFromAvailabilityOverride($availabilityOverride);

        foreach ($slots as $slot) {
            $this->deactivateSlot($slot);
        }

        $availabilityOverride->setActive(true);
        $this->doctrineHelper->save($availabilityOverride);
    }

    /**
     * Disattiva uno slot, lo rende non prenotabile, annulla eventuale appuntamento schedulato
     */
    private function deactivateSlot(AvailabilitySlot $slot, bool $cancelAppointment = true): void
    {
        $slot->setActive(false)->setBooked(false);

        if ($cancelAppointment) {
            $appointment = $slot->getScheduledAppointment(AppointmentStatusEnum::SCHEDULED);
            if ($appointment) {
                $appointment->setStatus(AppointmentStatusEnum::CANCELED);
                $this->doctrineHelper->save($appointment);
                // eventualmente trigger notifica cliente qui
            }
        }

        $this->doctrineHelper->save($slot);
    }

    /**
     * Riattiva uno slot, lo rende prenotabile
     */
    private function activateSlot(AvailabilitySlot $slot): void
    {
        $slot->setActive(true);
        $this->doctrineHelper->save($slot);
    }
}
