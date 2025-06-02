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
     * @param bool $override
     * 
     * @return AvailabilitySlot[]
     */
    public function getVisibleAvailabilitySlots(?AvailabilitySlotFilter $filter = null, ?User $user = null, ?bool $override = false): array
    {
        /** @var User $user */
        $user = is_null($user) ? $this->loggedUserService->getLoggedUser() : $user;

        /** @var AvailabilitySlotRepository $repo */
        $repo = $this->doctrineHelper->getRepository(AvailabilitySlot::class);

        $qb = $repo->createQbVisibleToUser(user: $user);

        if (!is_null($filter)) {

            $trainer = $filter->getTrainer();
            if (!is_null($trainer)) {
                $qb->andWhere('avs.trainer = :trainer')
                    ->setParameter('trainer', $trainer);
            }

            $date = $filter->getDate();
            if (!is_null($date)) {
                $qb->andWhere('avs.date = :date')
                    ->setParameter('date', $date);
            }

            // Orari
            $startTime = $filter->getStartTime();
            $endTime = $filter->getEndTime();
            if ($override) {
                if (!is_null($startTime)) {
                    $qb->andWhere('avs.startTime >= :startTime')
                        ->setParameter('startTime', $startTime);
                }

                if (!is_null($endTime)) {
                    $qb->andWhere('avs.endTime <= :endTime')
                        ->setParameter('endTime', $endTime);
                }
            } else {
                if (!is_null($startTime)) {
                    $qb->andWhere('avs.startTime = :startTime')
                        ->setParameter('startTime', $startTime);
                }

                if (!is_null($endTime)) {
                    $qb->andWhere('avs.endTime = :endTime')
                        ->setParameter('endTime', $endTime);
                }
            }

            $status = $filter->getStatus();
            if (!is_null($status)) {
                dump($status);
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
        $slotFilter = new AvailabilitySlotFilter()
            ->setDate($availabilityOverride->getDate())
            ->setStartTime($availabilityOverride->getStartTime())
            ->setEndTime($availabilityOverride->getEndTime())
            ->setTrainer($availabilityOverride->getTrainer())
            ->setStatus(true);

        return $this->getVisibleAvailabilitySlots(filter: $slotFilter, override: true);

        // return $repo->createBaseVisibleQb(trainer: $availabilityOverride->getTrainer())
        //     ->andWhere('avs.date = :date')
        //     ->setParameter('date', $availabilityOverride->getDate())
        //     ->andWhere('avs.startTime  >= :startTime')
        //     ->andWhere('startTime', $availabilityOverride->getStartTime())
        //     ->andWhere('avs.endTime  <= :endTime')
        //     ->andWhere('endTime', $availabilityOverride->getEndTime())
        //     ->andWhere('avs.active = true')
        //     ->getQuery()
        //     ->getResult();
    }
}
