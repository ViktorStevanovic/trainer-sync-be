<?php

namespace App\Services\User\Association;

use App\Entity\User\User;
use App\Enum\UserType\UserTypeEnum;
use App\Services\Client\ClientCreateManager;
use App\Services\Trainer\TrainerCreateManager;
use Symfony\Component\HttpFoundation\Request;

class AssociationUserManager
{
    public function __construct(
        private ClientCreateManager $clientCreateManager,
        private TrainerCreateManager $trainerCreateManager,
    ) {}

    public function createUserAssociation(User $user, Request $request): void
    {
        match ($user->getUserType()->getCode()) {
            UserTypeEnum::TRAINER => $this->trainerCreateManager->createTrainerUser(user: $user),
            UserTypeEnum::CLIENT => $this->clientCreateManager->createClientUser(user: $user, request: $request),
            default => []
        };
    }
}
