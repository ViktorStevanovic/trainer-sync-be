<?php

namespace App\Services\Appointment\Appointment;

use App\Entity\Appointment\Appointment\Appointment;
use App\Entity\User\User;
use App\Model\Form\Appointment\Appointment\AppointmentFilter;
use App\Repository\Appointment\Appointment\AppointmentRepository;
use App\Services\Utils\Helper\DoctrineHelper;
use App\Services\Utils\Helper\LoggedUserService;

readonly class AppointmentLister
{
    public function __construct(
        private DoctrineHelper $doctrineHelper,
        private LoggedUserService $loggedUserService
    ) {}


    /**
     * @param AppointmentFilter|null $filter
     * @param User|null $user
     * 
     * @return Appointment[]
     */
    public function getVisibleAppointments(?AppointmentFilter $filter = null, ?User $user = null): array
    {
        /** @var User $user */
        $user = is_null($user) ? $this->loggedUserService->getLoggedUser() : $user;

        /** @var AppointmentRepository $repo */
        $repo = $this->doctrineHelper->getRepository(Appointment::class);

        $qb = $repo->createQbVisibleToUser(user: $user);

        if (!is_null($filter)) {

            $trainer = $filter->getTrainer();
            if (!is_null($trainer)) {
                $qb->andWhere('a.trainer = :trainer')
                    ->setParameter('trainer', $trainer);
            }

            $client = $filter->getClient();
            if (!is_null($client)) {
                $qb->andWhere('a.client = :client')
                    ->setParameter('client', $client);
            }

            $date = $filter->getDate();
            if (!is_null($date)) {
                $qb->join('a.availabilitySlot', 'avs')
                    ->andWhere('avs.date = :date')
                    ->setParameter('date', $date);
            }

            $status = $filter->getStatus();
            if (!is_null($status)) {
                $qb->andWhere('a.status = :status')
                    ->setParameter('status', $status);
            }
        }

        return $qb->getQuery()->getResult();
    }
}
