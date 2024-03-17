<?php

declare(strict_types=1);

namespace My\RestBundle\Attribute\MyOpenApi;

use OpenApi\Attributes as OA;

/**
 * Represents a list of responses.
 */
class MyOpenApiResponseList
{
    /**
     * @param array<MyOpenApiResponse|OA\Response> $responses
     */
    public function __construct(
        private readonly array $responses
    ) {
    }

    /**
     * @return array<MyOpenApiResponse|OA\Response>
     */
    public function getResponses(): array
    {
        return $this->responses;
    }
}
