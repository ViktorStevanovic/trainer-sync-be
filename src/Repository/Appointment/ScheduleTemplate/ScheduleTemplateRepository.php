<?php

namespace App\Repository\Appointment\ScheduleTemplate;

use App\Entity\Trainer\Trainer;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;


class ScheduleTemplateRepository extends EntityRepository
{
    /**
     * @param Trainer $trainer
     * 
     * @return QueryBuilder
     */
    public function createBaseVisibleQb(Trainer $trainer): QueryBuilder
    {
        return $this->createBaseQb()
            ->andWhere('st.trainer = :trainer')
            ->setParameter('trainer', $trainer);
    }

    /**
     * @param string $alias
     * 
     * @return QueryBuilder
     */
    private function createBaseQb(?string $alias = 'st'): QueryBuilder
    {
        return $this->createQueryBuilder($alias);
    }
}
