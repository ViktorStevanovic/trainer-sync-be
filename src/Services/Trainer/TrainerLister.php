<?php

namespace App\Services\Trainer;

use App\Entity\Trainer\Trainer;
use App\Entity\User\User;
use App\Repository\Trainer\TrainerRepository;
use App\Services\Utils\Helper\DoctrineHelper;
use App\Services\Utils\Helper\LoggedUserService;

class TrainerLister
{
    public function __construct(
        private DoctrineHelper $doctrineHelper,
        private LoggedUserService $loggedUserService
    ) {}

    /**
     * @param User|null $user
     * 
     * @return array
     */
    public function getTrainersVisibleToUser(?User $user = null): array
    {
        /** @var User $user */
        $user = is_null($user) ? $this->loggedUserService->getLoggedUser() : $user;

        /** @var TrainerRepository $repo */
        $repo = $this->doctrineHelper->getRepository(Trainer::class);

        $qb = $repo->createBaseQbVisibleToUser(user: $user);

        return $qb->getQuery()->getResult();
    }


    /**
     * Metodo necessario per il comando di creazione slot
     * 
     * @return array
     */
    public function getAllActiveTrainers(): array
    {
        /** @var TrainerRepository $repo */
        $repo = $this->doctrineHelper->getRepository(Trainer::class);

        return $repo->createBaseQb()
            ->andWhere('t.active = 1')
            ->getQuery()
            ->getResult();
    }
}
