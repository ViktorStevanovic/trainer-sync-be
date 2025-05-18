<?php

namespace App\Services\Appointment\AvailabilityOverride;

use App\Entity\Appointment\AvailabilityOverride\AvailabilityOverride;
use App\Entity\User\User;
use App\Model\Form\Appointment\AvailabilityOverride\AvailabilityOverrideFilter;
use App\Repository\Appointment\AvailabilityOverride\AvailabilityOverrideRepository;
use App\Services\Utils\Helper\DoctrineHelper;
use App\Services\Utils\Helper\LoggedUserService;

readonly class AvailabilityOverrideLister
{
    public function __construct(
        private DoctrineHelper $doctrineHelper,
        private LoggedUserService $loggedUserService
    ) {}

    /**
     * @param AvailabilityOverrideFilter|null $filter
     * @param User|null $user
     * 
     * @return AvailabilityOverride[]
     */
    public function getTrainersAvailabilityOverrides(?AvailabilityOverrideFilter $filter = null, ?User $user = null): array
    {
        /** @var User $user */
        $user = is_null($user) ? $this->loggedUserService->getLoggedUser() : $user;

        /** @var AvailabilityOverrideRepository $repo */
        $repo = $this->doctrineHelper->getRepository(AvailabilityOverride::class);

        $qb = $repo->createBaseVisibleQb(trainer: $user->getTrainer());

        if (!is_null($filter)) {

            $date = $filter->getDate();
            if (!is_null($date)) {
                $qb->andWhere('ao.date = :date')
                    ->setParameter('date', $date);
            }

            $startTime = $filter->getStartTime();
            if (!is_null($startTime)) {
                $qb->andWhere('ao.startTime = :startTime')
                    ->setParameter('startTime', $startTime);
            }

            $endTime = $filter->getEndTime();
            if (!is_null($endTime)) {
                $qb->andWhere('ao.endTime = :endTime')
                    ->setParameter('endTime', $endTime);
            }

            $fullDayOverride = $filter->isFullDayOverride();
            if (!is_null($fullDayOverride)) {
                $qb->andWhere('ao.fullDayOverride = :fullDayOverride')
                    ->setParameter('fullDayOverride', $fullDayOverride);
            }
        }

        return $qb->getQuery()->getResult();
    }
}
