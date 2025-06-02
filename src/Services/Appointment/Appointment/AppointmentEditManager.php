<?php

namespace App\Services\Appointment\Appointment;

use Exception;
use App\Services\Utils\Helper\DoctrineHelper;
use Symfony\Component\HttpFoundation\Request;
use App\Services\Utils\Helper\LoggedUserService;
use Symfony\Component\Form\FormFactoryInterface;
use App\Entity\Appointment\Appointment\Appointment;
use App\Enum\Appointment\Appointment\AppointmentStatusEnum;
use App\Form\Appointment\Appointment\EditAppointmentType;
use DateTime;

readonly class AppointmentEditManager
{
    public function __construct(
        private DoctrineHelper $doctrineHelper,
        private FormFactoryInterface $formFactory,
        private LoggedUserService $loggedUserService,
    ) {}

    /**
     * @param Appointment $appointment
     * @param Request $request
     * 
     * @return void
     */
    public function manageAppointmentEdit(Appointment $appointment, Request $request): void
    {
        $oldSlot = $appointment->getAvailabilitySlot();
        $form = $this->formFactory->create(EditAppointmentType::class, $appointment);
        $form->submit($request->request->all());

        if (!$form->isValid()) {
            throw new Exception($form->getErrors(true));
        }

        $this->doctrineHelper->persist($appointment);
        // lo slot vecchio torna disponibile (non prenotato)
        $oldSlot->setBooked(false);

        // lo slot nuovo va messo a prenotato
        $appointment->getAvailabilitySlot()->setBooked(true);

        $this->doctrineHelper->flush();
    }

    /**
     * @param Appointment $appointment
     * @param string $status
     * 
     * @return void
     */
    public function manageAppointmentStatus(Appointment $appointment, string $status): void
    {
        $now = new DateTime();

        $slotDate = $appointment->getAvailabilitySlot()->getDate();
        $slotEndTime = $appointment->getAvailabilitySlot()->getEndTime();

        // DateTime dello slot
        $slotEndDateTime = new DateTime(
            $slotDate->format('Y-m-d') . ' ' . $slotEndTime->format('H:i:s')
        );

        // se cerca di completare un appuntamento prima della sua data do errore
        if (
            $status === AppointmentStatusEnum::COMPLETED &&
            $slotEndDateTime > $now
        ) {
            throw new Exception(message: 'Non puoi completare un appuntamento prima della sua data');
        }

        // se cancello, lo slot torna disponibile
        if ($status === AppointmentStatusEnum::CANCELED) {
            $appointment->getAvailabilitySlot()->setBooked(false);
        }
    }
}
