<?php

namespace App\Repository\Appointment\AvailabilityOverride;

use App\Entity\Trainer\Trainer;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;


class AvailabilityOverrideRepository extends EntityRepository
{
    /**
     * @param Trainer $trainer
     * 
     * @return QueryBuilder
     */
    public function createBaseVisibleQb(Trainer $trainer): QueryBuilder
    {
        return $this->createBaseQb()
            ->andWhere('ao.trainer = :trainer')
            ->setParameter('trainer', $trainer);
    }

    /**
     * @param string $alias
     * 
     * @return QueryBuilder
     */
    private function createBaseQb(?string $alias = 'ao'): QueryBuilder
    {
        return $this->createQueryBuilder($alias);
    }
}
