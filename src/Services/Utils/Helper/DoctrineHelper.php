<?php

namespace App\Services\Utils\Helper;

use Doctrine\Common\Util\ClassUtils;
use Doctrine\DBAL\Cache\QueryCacheProfile;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\LockMode;
use Doctrine\DBAL\Query\QueryBuilder as DBALQueryBuilder;
use Doctrine\DBAL\Result;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\PessimisticLockException;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder as ORMQueryBuilder;
use Doctrine\ORM\UnitOfWork;
use Doctrine\Persistence\ObjectRepository;
use InvalidArgumentException;
use ReflectionMethod;

readonly class DoctrineHelper
{
    public function __construct(
        private EntityManagerInterface $em
    ) {}

    /**
     *
     * @return EntityManagerInterface
     */
    public function getEntityManager(): EntityManagerInterface
    {
        return $this->em;
    }

    /**
     *
     * @return Connection
     */
    public function getConnection(): Connection
    {
        return $this->getEntityManager()->getConnection();
    }

    /**
     * @return UnitOfWork
     * @see https://stackoverflow.com/questions/9057558/is-there-a-built-in-way-to-get-all-of-the-changed-updated-fields-in-a-doctrine-2
     */
    public function getUnitOfWork(): UnitOfWork
    {
        return $this->getEntityManager()->getUnitOfWork();
    }

    /**
     * @param string $className
     * @return ObjectRepository
     */
    public function getRepository(string $className): ObjectRepository
    {
        return $this->getEntityManager()->getRepository($className);
    }

    /**
     * Gets an ExpressionBuilder used for object-oriented construction of query expressions.
     *
     * @return Expr
     */
    public function expr(): Expr
    {
        return $this->getEntityManager()->getExpressionBuilder();
    }

    /**
     * Effettuo il lock dell'entity
     *
     * @param object $entity
     * @param int $entityVersion
     * @param int $lockMode
     * @return void
     *
     * @throws OptimisticLockException
     * @throws PessimisticLockException
     */
    public function lock(object $entity, int $entityVersion, int $lockMode = LockMode::OPTIMISTIC): void
    {
        $this->getEntityManager()->lock($entity, $lockMode, $entityVersion);
    }

    /**
     * @param object|array $entities Se è un object fa il persist di $entities, se è un array fa il persist di tutti gli elementi di $entities, se null non fa persist
     */
    public function persist(object|array $entities = []): void
    {
        $em = $this->getEntityManager();
        if (is_object($entities)) {
            $entities = [$entities];
        }
        if (!$entities) {
            $entities = [];
        }
        foreach ($entities as $entity) {
            $em->persist($entity);
        }
    }

    /**
     * @param object|array $entities Se è un object fa il persist di $entities, se è un array fa il persist di tutti gli elementi di $entities, se null non fa persist
     */
    public function save(object|array $entities = []): void
    {
        $this->persist($entities);

        $this->flush();
    }

    /**
     * @param object|array $entities Se è un object fa il refresh di $entities, se è un array fa il refresh di tutti gli elementi di $entities, se null non fa niente
     */
    public function refresh(object|array $entities = []): void
    {
        $em = $this->getEntityManager();
        if (is_object($entities)) {
            $entities = [$entities];
        }
        if (!$entities) {
            $entities = [];
        }
        foreach ($entities as $entity) {
            $em->refresh($entity);
        }
    }

    /**
     * @param object|array $entities Se è un object fa il remove di $entities, se è un array fa il remove di tutti gli elementi di $entities, se null non fa remove
     */
    public function remove(object|array $entities = []): void
    {
        $em = $this->getEntityManager();
        if (is_object($entities)) {
            $entities = [$entities];
        }
        if (!$entities) {
            $entities = [];
        }
        foreach ($entities as $entity) {
            $em->remove($entity);
        }

        $this->flush();
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }


    public function beginTransaction(): void
    {
        $this->getEntityManager()->beginTransaction();
    }

    public function commit(): void
    {
        $this->getEntityManager()->commit();
    }

    public function rollback(): void
    {
        $this->getEntityManager()->rollback();
    }

    /**
     * Executes an, optionally parametrized, SQL query.
     * If the query is parametrized, a prepared statement is used. If an SQLLogger is configured, the execution is logged.
     *
     * @param string $sql
     * @param array $params
     * @param array $types
     * @param QueryCacheProfile|null $qcp
     * @return Result
     * @throws Exception
     */
    public function executeQuery(string $sql, array $params = [], array $types = [], ?QueryCacheProfile $qcp = null): Result
    {
        return $this->getConnection()->executeQuery(sql: $sql, params: $params, types: $types, qcp: $qcp);
    }

    /**
     * @param object $entity
     * @return void
     */
    public function detach(object $entity): void
    {
        $this->getEntityManager()->detach($entity);
    }

    /**
     * Verifico se è cambiata almeno una proprietà dell'entità
     *
     * @param object $entity
     * @return bool
     */
    public function isAtLeastAPropertyChanged(object $entity): bool
    {
        $properties = array_keys($this->getUnitOfWork()->getOriginalEntityData($entity));

        foreach ($properties as $property) {
            # il getOriginalEntityData() restituisce anche le FK; se la proprietà non esiste nella classe, dunque, la ignoro
            if (!property_exists($entity, $property)) {
                continue;
            }
            if ($this->isEntityPropertyChanged(entity: $entity, changedField: $property)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Recupero le proprietà modificate dell'entity
     *
     * @param object $entity
     * @return array
     */
    public function getEntityChangedProperties(object $entity): array
    {
        $properties = array_keys($this->getUnitOfWork()->getOriginalEntityData($entity));
        $changedProperties = [];
        foreach ($properties as $property) {
            if ($this->isEntityPropertyChanged(entity: $entity, changedField: $property)) {
                $changedProperties[] = $property;
            }
        }

        return $changedProperties;
    }

    /**
     * Verifico se la proprietà specificata è cambiata
     *
     * @param object $entity
     * @param string $changedField
     * @return bool
     */
    public function isEntityPropertyChanged(object $entity, string $changedField): bool
    {
        $entityClass = ClassUtils::getClass(object: $entity);
        $originalData = $this->getUnitOfWork()->getOriginalEntityData($entity);
        if (!array_key_exists($changedField, $originalData) || !property_exists($entityClass, $changedField)) {
            throw new InvalidArgumentException(sprintf("The field '%s' wasn't found in entity '%s' class", $changedField, get_class($entity)));
        }
        $originalValue = $originalData[$changedField];

        # invoco il metodo getter per recuperare il valore corrente della proprietà
        $getterMethod = sprintf("get%s", ucfirst($changedField));
        $isGetterMethod = sprintf("is%s", ucfirst($changedField));
        if (method_exists($entityClass, $getterMethod)) {
            $reflectionGetterMethod = new ReflectionMethod($entity, $getterMethod);
            $currentValue = $reflectionGetterMethod->invoke($entity);
        } elseif (method_exists($entityClass, $isGetterMethod)) {
            $reflectionGetterMethod = new ReflectionMethod($entity, $isGetterMethod);
            $currentValue = $reflectionGetterMethod->invoke($entity);
        } else {
            throw new InvalidArgumentException(sprintf("No getter method which begins with get/is for field '%s' was found in entity '%s' class", $changedField, get_class($entity)));
        }

        return $currentValue !== $originalValue;
    }


    /**
     * Crea un query builder per ORM
     *
     * @return ORMQueryBuilder
     */
    public function createORMQueryBuilder(): ORMQueryBuilder
    {
        return $this->getEntityManager()->createQueryBuilder();
    }

    /**
     * Crea un query builder per DBAL
     *
     * @return DBALQueryBuilder
     */
    public function createDBALQueryBuilder(): DBALQueryBuilder
    {
        return $this->getConnection()->createQueryBuilder();
    }
}
