<?php

namespace App\Services\Utils\Helper;

use App\App;
use App\Error\ErrorCodeEnum;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

readonly class JsonResponseHelper
{
    public function __construct(
        private SerializerHelper $serializerHelper
    ) {}

    /**
     * @param array|object|string $data
     * @param array $groups
     * @param int $statusCode
     *
     * @return JsonResponse
     */
    public function renderSerializedData(mixed $data = [], array $groups = [], int $statusCode = Response::HTTP_OK): JsonResponse
    {
        return JsonResponse::fromJsonString(
            $this->serializerHelper->serialize($data, $groups),
            $statusCode
        );
    }

    /**
     * @param FormInterface $form
     * @param int $statusCode
     *
     * @return JsonResponse
     */
    public function renderSerializedFormErrors(FormInterface $form, int $statusCode = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        $error = $form->getErrors(deep: true)->current();

        return JsonResponse::fromJsonString(
            $this->serializerHelper->serialize(['form' => $form, 'message' => $error ? $error->getMessage() : ErrorCodeEnum::ERROR_DEFAULT_ERROR_001]),
            $statusCode
        );
    }

    /**
     * @param string $message
     * @param int $statusCode
     *
     * @return JsonResponse
     */
    public function renderSerializedErrorMessage(string $message = ErrorCodeEnum::ERROR_DEFAULT_ERROR_001, int $statusCode = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        return new JsonResponse(['message' => $message], $statusCode);
    }

    /**
     * @param int $statusCode
     *
     * @return JsonResponse
     */
    public function renderEmptyResponse(int $statusCode = Response::HTTP_OK): JsonResponse
    {
        return $this->renderSerializedData([], [], $statusCode);
    }
}
