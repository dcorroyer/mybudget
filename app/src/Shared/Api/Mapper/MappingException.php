<?php

declare(strict_types=1);

namespace App\Shared\Api\Mapper;

class MappingException extends \RuntimeException
{
    public static function fromPayloadToEntity(string $payloadClass, string $entityClass): self
    {
        return new self(\sprintf('Cannot map payload class %s to entity class %s', $payloadClass, $entityClass));
    }
}
