<?php

declare(strict_types=1);

namespace My\RestBundle\Attribute\MyOpenApi\Response;

use Symfony\Component\HttpFoundation\Response;
use My\RestBundle\Attribute\MyOpenApi\MyOpenApiResponse;

/**
 * This class is used to represent a bad request response.
 */
class BadRequestResponse extends MyOpenApiResponse
{
    /**
     * @param array<string> $groups
     */
    public function __construct(
        string $responseClassFqcn,
        array  $groups = [],
        int    $responseCode = Response::HTTP_BAD_REQUEST,
        string $description = 'When the request is malformed or invalid',
    ) {
        parent::__construct(
            description: $description,
            responseClassFqcn: $responseClassFqcn,
            responseCode: $responseCode,
            groups: $groups,
            asArray: false
        );
    }
}
