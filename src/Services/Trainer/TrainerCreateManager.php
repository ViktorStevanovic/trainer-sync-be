<?php

namespace App\Services\Trainer;

use App\Entity\Trainer\Trainer;
use App\Entity\User\User;
use App\Services\Utils\Helper\DoctrineHelper;

class TrainerCreateManager
{
    public function __construct(
        private readonly DoctrineHelper $doctrineHelper,
    ) {}

    /**
     * @param User $user
     * 
     * @return void
     */
    public function createTrainerUser(User $user): void
    {
        $trainer = new Trainer()
            ->setUser($user);

        $this->doctrineHelper->persist($trainer);
    }
}
