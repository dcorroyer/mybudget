<?php

declare(strict_types=1);

namespace My\RestBundle\Serialization\Model;

use My\RestBundle\Serialization\ApiSerializationGroups;
use OpenApi\Attributes as OA;
use Stringable;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @codeCoverageIgnore
 */
final class Error
{
    #[Groups([ApiSerializationGroups::API_ERROR])]
    private string $propertyPath;


    #[Groups([ApiSerializationGroups::API_ERROR])]
    #[OA\Property(type: '', example: 'mixed value can be anything')]
    private mixed $value;

    /**
     * @var array<string|Stringable>
     */
    #[Groups([ApiSerializationGroups::API_ERROR])]
    private array $errors;

    /**
     * @param array<int, string|Stringable> $errors
     */
    public function __construct(string $propertyPath, mixed $value, array $errors)
    {
        $this->propertyPath = $propertyPath;
        $this->value = $value;
        $this->errors = $errors;
    }

    public function getPropertyPath(): string
    {
        return $this->propertyPath;
    }

    public function setPropertyPath(string $propertyPath): self
    {
        $this->propertyPath = $propertyPath;

        return $this;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }


    public function setValue(mixed $value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return array<string|Stringable>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param array<string|Stringable> $errors
     */
    public function setErrors(array $errors): self
    {
        $this->errors = $errors;

        return $this;
    }


    public function addError(Stringable|string $error): self
    {
        $this->errors[] = (string) $error;

        return $this;
    }
}
