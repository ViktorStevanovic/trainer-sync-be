<?php

namespace App\Controller;

use App\Entity\User\User;
use App\Error\ErrorCodeEnum;
use App\Services\Utils\Helper\DoctrineHelper;
use App\Services\Utils\Helper\JsonResponseHelper;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\PessimisticLockException;
use Doctrine\ORM\UnitOfWork;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * @method User getUser()
 */
class Controller extends AbstractController
{
    protected readonly JsonResponseHelper $jsonResponseHelper;
    protected readonly DoctrineHelper $doctrineHelper;

    #[Required]
    public function setJsonResponseHelper(JsonResponseHelper $jsonResponseHelper): void
    {
        $this->jsonResponseHelper = $jsonResponseHelper;
    }

    #[Required]
    public function setDoctrineHelper(DoctrineHelper $doctrineHelper): void
    {
        $this->doctrineHelper = $doctrineHelper;
    }


    //////////////////////////////////////////////////// SERIALIZER ////////////////////////////////////////////////////

    /**
     * @param array|object|string $data
     * @param array $groups
     * @param int $statusCode
     *
     * @return JsonResponse
     */
    protected function renderSerializedData(mixed $data = [], array $groups = [], int $statusCode = Response::HTTP_OK): JsonResponse
    {
        return $this->jsonResponseHelper->renderSerializedData(data: $data, groups: $groups, statusCode: $statusCode);
    }

    /**
     * @param FormInterface $form
     * @param int $statusCode
     *
     * @return JsonResponse
     */
    protected function renderSerializedFormErrors(FormInterface $form, int $statusCode = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        return $this->jsonResponseHelper->renderSerializedFormErrors(form: $form, statusCode: $statusCode);
    }

    /**
     * @param string|\BackedEnum|null $message
     * @param int $statusCode
     *
     * @return JsonResponse
     */
    protected function renderSerializedErrorMessage(null|string|\BackedEnum $message = ErrorCodeEnum::ERROR_DEFAULT_ERROR_001, int $statusCode = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        return $this->jsonResponseHelper->renderSerializedErrorMessage(message: $message, statusCode: $statusCode);
    }

    public function renderSerializedLockedErrorMessage(): JsonResponse
    {
        return $this->renderSerializedErrorMessage(message: ErrorCodeEnum::ERROR_ENTITY_002, statusCode: Response::HTTP_FORBIDDEN);
    }

    /**
     * @param int $statusCode
     *
     * @return JsonResponse
     */
    protected function renderEmptyResponse(int $statusCode = Response::HTTP_OK): JsonResponse
    {
        return $this->jsonResponseHelper->renderEmptyResponse(statusCode: $statusCode);
    }

    ///////////////////////////////////////////////////// DOCTRINE /////////////////////////////////////////////////////

    /**
     *
     * @return EntityManagerInterface
     */
    protected function getEntityManager(): EntityManagerInterface
    {
        return $this->doctrineHelper->getEntityManager();
    }

    /**
     * @return UnitOfWork
     * @see https://stackoverflow.com/questions/9057558/is-there-a-built-in-way-to-get-all-of-the-changed-updated-fields-in-a-doctrine-2
     */
    public function getUnitOfWork(): UnitOfWork
    {
        return $this->doctrineHelper->getUnitOfWork();
    }

    /**
     * @param string $className
     * @return ObjectRepository
     */
    protected function getRepository(string $className): ObjectRepository
    {
        return $this->doctrineHelper->getRepository($className);
    }

    /**
     * @param object|array $entities Se è un object fa il persist di $entities, se è un array fa il persist di tutti gli elementi di $entities, se null non fa persist
     */
    protected function persist(object|array $entities = []): void
    {
        $this->doctrineHelper->persist($entities);
    }

    /**
     * @param object|array $entities Se è un object fa il persist+flush di $entities, se è un array fa il persist+flush di tutti gli elementi di $entities, se null non fa persist+flush
     */
    protected function save(object|array $entities = []): void
    {
        $this->doctrineHelper->save($entities);
    }

    /**
     * @param object|array $entities Se è un object fa il remove di $entities, se è un array fa il remove di tutti gli elementi di $entities, se null non fa remove
     */
    protected function remove(object|array $entities = []): void
    {
        $this->doctrineHelper->remove($entities);
    }

    /**
     * @param object|array $entities Se è un object fa il refresh di $entities, se è un array fa il refresh di tutti gli elementi di $entities, se null non fa refresh
     */
    protected function refresh(object|array $entities = []): void
    {
        $this->doctrineHelper->refresh($entities);
    }

    public function flush(): void
    {
        $this->doctrineHelper->flush();
    }

    public function beginTransaction(): void
    {
        $this->doctrineHelper->beginTransaction();
    }

    public function commit(): void
    {
        $this->doctrineHelper->commit();
    }

    public function rollback(): void
    {
        $this->doctrineHelper->rollback();
    }

    public function detach(mixed $entity): void
    {
        $this->doctrineHelper->detach($entity);
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
        $this->doctrineHelper->lock($entity, $lockMode, $entityVersion);
    }

    /**
     * Verifico se è cambiata almeno una proprietà dell'entità
     *
     * @param object $entity
     * @return bool
     */
    public function isAtLeastAPropertyChanged(object $entity): bool
    {
        return $this->doctrineHelper->isAtLeastAPropertyChanged(entity: $entity);
    }

    /**
     * Recupero le proprietà modificate dell'entity
     *
     * @param object $entity
     * @return array
     */
    public function getEntityChangedProperties(object $entity): array
    {
        return $this->doctrineHelper->getEntityChangedProperties(entity: $entity);
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
        return $this->doctrineHelper->isEntityPropertyChanged(entity: $entity, changedField: $changedField);
    }
}
