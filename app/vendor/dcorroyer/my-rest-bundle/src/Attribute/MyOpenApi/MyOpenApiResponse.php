<?php

declare(strict_types=1);

namespace My\RestBundle\Attribute\MyOpenApi;

use Symfony\Component\HttpFoundation\Response;

/**
 * Represents a single response.
 */
class MyOpenApiResponse
{
    /**
     * @param array<string> $groups
     */
    public function __construct(
        private readonly string  $description,
        private readonly ?string  $responseClassFqcn = null,
        private readonly int     $responseCode = Response::HTTP_OK,
        private readonly array   $groups = [],
        private readonly bool    $asArray = false,
        private readonly ?string $metaClassFqcn = null,
    ) {
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getResponseClassFqcn(): ?string
    {
        return $this->responseClassFqcn;
    }

    public function getResponseCode(): int
    {
        return $this->responseCode;
    }

    /**
     * @return array<string>
     */
    public function getGroups(): array
    {
        return $this->groups;
    }

    public function isAsArray(): bool
    {
        return $this->asArray;
    }

    public function getMetaClassFqcn(): ?string
    {
        return $this->metaClassFqcn;
    }
}
