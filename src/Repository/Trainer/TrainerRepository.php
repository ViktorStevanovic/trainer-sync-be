<?php

namespace App\Repository\Trainer;

use App\Entity\User\User;
use App\Enum\UserType\UserTypeEnum;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;


class TrainerRepository extends EntityRepository
{
    /**
     * @param User $user
     * 
     * @return QueryBuilder
     */
    public function createBaseQbVisibleToUser(User $user): QueryBuilder
    {
        return match ($user->getUserType()->getCode()) {

            UserTypeEnum::ADMIN => $this->createBaseQbVisibleToAdmin(user: $user),

            UserTypeEnum::TRAINER => $this->createBaseQbVisibleToTrainer(user: $user),

            UserTypeEnum::CLIENT => $this->createBaseQbVisibleToClient(user: $user),
        };
    }


    /**
     * @param User $user
     * 
     * @return QueryBuilder
     */
    private function createBaseQbVisibleToAdmin(User $user): QueryBuilder
    {
        return $this->createBaseQb();
    }


    /**
     * @param User $user
     * 
     * @return QueryBuilder
     */
    private function createBaseQbVisibleToTrainer(User $user): QueryBuilder
    {
        return $this->createBaseQb()
            ->andWhere('t.active = 1');
    }


    /**
     * @param User $user
     * 
     * @return QueryBuilder
     */
    private function createBaseQbVisibleToClient(User $user): QueryBuilder
    {
        return $this->createBaseQb()
            ->andWhere('t.active = 1')
            ->join('t.clients', 'c')
            ->andWhere('c.trainer = :trainer')
            ->setParameter('trainer', $user->getTrainer());
    }

    /**
     * @param string $alias
     * 
     * @return QueryBuilder
     */
    public function createBaseQb(?string $alias = 't'): QueryBuilder
    {
        return $this->createQueryBuilder($alias);
    }
}
