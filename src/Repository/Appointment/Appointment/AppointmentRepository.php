<?php

namespace App\Repository\Appointment\Appointment;

use App\Entity\Client\Client;
use App\Entity\Trainer\Trainer;
use App\Entity\User\User;
use App\Enum\UserType\UserTypeEnum;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class AppointmentRepository extends EntityRepository
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
     * Recupero gli appuntamenti del client loggato
     * 
     * @param Client $client
     * 
     * @return QueryBuilder
     */
    private function createBaseVisibleToClient(Client $client): QueryBuilder
    {
        return $this->createBaseQb()
            ->andWhere('a.client = :client')
            ->setParameter('client', $client);
    }

    /**
     * Recupero solo gli appuntamenti del trainer loggato
     * 
     * @param Trainer $trainer
     * 
     * @return QueryBuilder
     */
    private function createBaseVisibleToTrainer(Trainer $trainer): QueryBuilder
    {
        return $this->createBaseQb()
            ->andWhere('a.trainer = :trainer')
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
    private function createBaseQb(?string $alias = 'a'): QueryBuilder
    {
        return $this->createQueryBuilder($alias);
    }
}
