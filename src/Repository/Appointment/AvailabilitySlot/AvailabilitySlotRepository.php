<?php

namespace App\Repository\Appointment\AvailabilitySlot;

use App\Entity\Client\Client;
use App\Entity\Trainer\Trainer;
use App\Entity\User\User;
use App\Enum\UserType\UserTypeEnum;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;


class AvailabilitySlotRepository extends EntityRepository
{
    /**
     * @param User $user
     * 
     * @return QueryBuilder
     */
    public function createQbVisibleToUser(User $user): QueryBuilder
    {
        return match ($user->getUserType()->getCode()) {
            UserTypeEnum::ADMIN => $this->createBaseVisibleToAdmin(),
            UserTypeEnum::TRAINER => $this->createBaseVisibleToTrainer($user->getTrainer()),
            UserTypeEnum::CLIENT => $this->createBaseVisibleToClient($user->getClient()),
        };
    }

    /**
     * Recupero gli slot visibili al cliente (quelli del suo trainer)
     * 
     * @param Client $client
     * 
     * @return QueryBuilder
     */
    private function createBaseVisibleToClient(Client $client): QueryBuilder
    {
        return $this->createBaseQb()
            ->join('avs.trainer', 't')
            ->andWhere(':client member of t.clients')
            ->setParameter('client', $client);
    }

    /**
     * Recupero solo gli slot del trainer loggato
     * 
     * @param Trainer $trainer
     * 
     * @return QueryBuilder
     */
    private function createBaseVisibleToTrainer(Trainer $trainer): QueryBuilder
    {
        return $this->createBaseQb()
            ->andWhere('avs.trainer = :trainer')
            ->setParameter('trainer', $trainer);
    }

    /**
     * @return QueryBuilder
     */
    private function createBaseVisibleToAdmin(): QueryBuilder
    {
        return $this->createBaseQb();
    }


    /**
     * @param string $alias
     * 
     * @return QueryBuilder
     */
    private function createBaseQb(?string $alias = 'avs'): QueryBuilder
    {
        return $this->createQueryBuilder($alias);
    }
}
