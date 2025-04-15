<?php

namespace App\Services\Utils\Helper;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;

readonly class SerializerHelper
{
    public function __construct(
        private SerializerInterface $serializer
    ) {}

    /**
     * @param object|array|scalar $data
     * @param array $groups
     * @param string $format
     * @param string|null $type
     * @return string
     */
    public function serialize(mixed $data, array $groups = [], string $format = 'json', ?string $type = null): string
    {
        $serializationContext = SerializationContext::create()->setSerializeNull(true);
        if ($groups) {
            $serializationContext->setGroups($groups);
        }

        return $this->serializer->serialize(data: $data, format: $format, context: $serializationContext, type: $type);
    }
}
