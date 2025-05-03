<?php

namespace App\Repository;

use App\Entity\User\User;
use App\Enum\UserType\UserTypeEnum;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;


class UserRepository extends EntityRepository
{
    /**
     * @param User $user
     * 
     * @return QueryBuilder
     */
    public function createBaseQbVisibleToUser(User $user): QueryBuilder
    {
        return match ($user->getUserType()->getCode()) {

            UserTypeEnum::ADMIN => $this->createBaseQbVisibleToAdmin(),

            UserTypeEnum::TRAINER => $this->createBaseQbVisibleToTrainer(),

            UserTypeEnum::CLIENT => $this->createBaseQbVisibleToClient(),
        };
    }

    /**
     * @return QueryBuilder
     */
    private function createBaseQbVisibleToAdmin(): QueryBuilder
    {
        return $this->createBaseQb();
    }

    /**
     * @return QueryBuilder
     */
    private function createBaseQbVisibleToTrainer(): QueryBuilder
    {
        return $this->createBaseQb();
    }

    /**
     * @return QueryBuilder
     */
    private function createBaseQbVisibleToClient(): QueryBuilder
    {
        return $this->createBaseQb();
    }

    /**
     * @param string $alias
     * 
     * @return QueryBuilder
     */
    private function createBaseQb(?string $alias = 'u'): QueryBuilder
    {
        return $this->createQueryBuilder($alias);
    }
}
