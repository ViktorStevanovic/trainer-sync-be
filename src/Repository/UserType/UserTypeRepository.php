<?php

namespace App\Repository\UserType;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class UserTypeRepository extends EntityRepository
{
    public function createBaseQb(): QueryBuilder
    {
        return $this->createQueryBuilder('ut');
    }
}
