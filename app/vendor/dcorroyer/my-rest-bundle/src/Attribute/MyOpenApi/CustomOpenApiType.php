<?php

declare(strict_types=1);

namespace My\RestBundle\Attribute\MyOpenApi;

class CustomOpenApiType
{
    public function __construct(
        private readonly string $name,
        private readonly ?string $format = null,
        private readonly bool $isBuiltInType = false,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getFormat(): ?string
    {
        return $this->format;
    }

    public function isBuiltInType(): bool
    {
        return $this->isBuiltInType;
    }
}
