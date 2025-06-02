<?php

namespace App\Services\Appointment\Appointment;

use Exception;
use App\Entity\User\User;
use App\Enum\UserType\UserTypeEnum;
use Symfony\Component\Form\FormInterface;
use App\Services\Utils\Helper\DoctrineHelper;
use Symfony\Component\HttpFoundation\Request;
use App\Services\Utils\Helper\LoggedUserService;
use Symfony\Component\Form\FormFactoryInterface;
use App\Entity\Appointment\Appointment\Appointment;
use App\Form\Appointment\Appointment\CreateAppointmentType;
use App\Form\Appointment\Appointment\Association\TrainerAppointmentType;
use App\Form\Appointment\Appointment\Association\Association\ClientAppointmentType;

readonly class AppointmentCreateManager
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
    public function manageAppointmentCreation(Appointment $appointment, Request $request): void
    {
        /** @var User $user */
        $user = $this->loggedUserService->getLoggedUser();

        $form = match ($user->getUserType()->getCode()) {
            UserTypeEnum::ADMIN => $this->adminAppointmentFactory($appointment, $user),
            UserTypeEnum::TRAINER => $this->trainerAppointmentFactory($appointment, $user),
            UserTypeEnum::CLIENT => $this->clientAppointmentFactory($appointment, $user),
            default => []
        };

        $form->submit($request->request->all());
        if (!$form->isValid()) {
            throw new Exception($form->getErrors(true));
        }
        // metto lo slot a prenotato
        $appointment->getAvailabilitySlot()->setBooked(true);

        $this->doctrineHelper->save($appointment);
    }

    /**
     * @param Appointment $appointment
     * @param User $user
     * 
     * @return FormInterface
     */
    private function trainerAppointmentFactory(Appointment $appointment, User $user): FormInterface
    {
        $appointment->setTrainer($user->getTrainer());
        return $this->formFactory->create(TrainerAppointmentType::class, $appointment);
    }

    /**
     * @param Appointment $appointment
     * @param User $user
     * 
     * @return FormInterface
     */
    private function clientAppointmentFactory(Appointment $appointment, User $user): FormInterface
    {
        $appointment->setClient($user->getClient());
        return $this->formFactory->create(ClientAppointmentType::class, $appointment);
    }

    /**
     * @param Appointment $appointment
     * @param User $user
     * 
     * @return FormInterface
     */
    private function adminAppointmentFactory(Appointment $appointment, User $user): FormInterface
    {
        return $this->formFactory->create(CreateAppointmentType::class, $appointment);
    }
}
