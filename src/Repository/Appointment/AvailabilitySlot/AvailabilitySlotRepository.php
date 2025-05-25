<?php

namespace App\Repository\Appointment\AvailabilitySlot;

use App\Entity\Trainer\Trainer;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;


class AvailabilitySlotRepository extends EntityRepository
{
    /**
     * @param Trainer $trainer
     * 
     * @return QueryBuilder
     */
    public function createBaseVisibleQb(Trainer $trainer): QueryBuilder
    {
        return $this->createBaseQb()
            ->andWhere('avs.trainer = :trainer')
            ->setParameter('trainer', $trainer);
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
