<?php

namespace App\Services\Appointment\AvailabilitySlot;

use App\Entity\Appointment\AvailabilityOverride\AvailabilityOverride;
use App\Entity\Appointment\AvailabilitySlot\AvailabilitySlot;
use App\Entity\User\User;
use App\Model\Form\Appointment\AvailabilitySlot\AvailabilitySlotFilter;
use App\Repository\Appointment\AvailabilitySlot\AvailabilitySlotRepository;
use App\Services\Utils\Helper\DoctrineHelper;
use App\Services\Utils\Helper\LoggedUserService;

readonly class AvailabilitySlotLister
{
    public function __construct(
        private DoctrineHelper $doctrineHelper,
        private LoggedUserService $loggedUserService
    ) {}

    /**
     * @param AvailabilitySlotFilter|null $filter
     * @param User|null $user
     * 
     * @return AvailabilitySlot[]
     */
    public function getTrainersAvailabilitySlots(?AvailabilitySlotFilter $filter = null, ?User $user = null): array
    {
        /** @var User $user */
        $user = is_null($user) ? $this->loggedUserService->getLoggedUser() : $user;

        /** @var AvailabilitySlotRepository $repo */
        $repo = $this->doctrineHelper->getRepository(AvailabilitySlot::class);

        $qb = $repo->createBaseVisibleQb(trainer: $user->getTrainer());

        if (!is_null($filter)) {

            $date = $filter->getDate();
            if (!is_null($date)) {
                $qb->andWhere('avs.date = :date')
                    ->setParameter('date', $date);
            }

            $startTime = $filter->getStartTime();
            if (!is_null($startTime)) {
                $qb->andWhere('avs.startTime = :startTime')
                    ->setParameter('startTime', $startTime);
            }

            $endTime = $filter->getEndTime();
            if (!is_null($endTime)) {
                $qb->andWhere('avs.endTime = :endTime')
                    ->setParameter('endTime', $endTime);
            }

            $status = $filter->getStatus();
            if (!is_null($status)) {
                $qb->andWhere('avs.active = :active')
                    ->setParameter('active', $status);
            }

            $booked = $filter->isBooked();
            if (!is_null($booked)) {
                $qb->andWhere('avs.booked = :booked')
                    ->setParameter('booked', $booked);
            }
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * @param AvailabilityOverride $availabilityOverride
     * 
     * @return AvailabilitySlot[]
     */
    public function getSlotsFromAvailabilityOverride(AvailabilityOverride $availabilityOverride): array
    {
        /** @var AvailabilitySlotRepository $repo */
        $repo = $this->doctrineHelper->getRepository(AvailabilitySlot::class);

        return $repo->createBaseVisibleQb(trainer: $availabilityOverride->getTrainer())
            ->andWhere('avs.date = :date')
            ->setParameter('date', $availabilityOverride->getDate())
            ->andWhere('avs.startTime  >= :startTime')
            ->andWhere('startTime', $availabilityOverride->getStartTime())
            ->andWhere('avs.endTime  <= :endTime')
            ->andWhere('endTime', $availabilityOverride->getEndTime())
            ->andWhere('avs.active = true')
            ->getQuery()
            ->getResult();
    }
}
