<?php

namespace App\Services\Appointment\Appointment;

use App\Entity\Appointment\Appointment\Appointment;
use App\Entity\User\User;
use App\Services\Utils\Helper\LoggedUserService;

class AppointmentChecker
{
    public function __construct(
        private readonly AppointmentLister $appointmentLister,
        private readonly LoggedUserService $loggedUserService
    ) {}

    /**
     * Controllo che l'appuntamento passato in input possa essere visto dall'utente loggato
     * @param Appointment $appointment
     * @param User|null $user
     * 
     * @return bool
     */
    public function checkVisibleAppointment(Appointment $appointment, ?User $user = null): bool
    {
        /** @var User $user */
        $user = is_null($user) ? $this->loggedUserService->getLoggedUser() : $user;

        $appointments = $this->appointmentLister->getVisibleAppointments(user: $user);
        return in_array($appointment, $appointments);
    }
}
