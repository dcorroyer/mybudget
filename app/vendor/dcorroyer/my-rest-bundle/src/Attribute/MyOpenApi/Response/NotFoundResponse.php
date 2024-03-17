<?php

declare(strict_types=1);

namespace My\RestBundle\Attribute\MyOpenApi\Response;

use Symfony\Component\HttpFoundation\Response;

/**
 * This class is used to represent a Not Found response.
 *
 * Corresponding to RestControllerTrait::notFoundResponse()
 *
 * @see RestControllerTrait::notFoundResponse()
 */
class NotFoundResponse extends ErrorResponse
{
    public function __construct(
        string $description = 'Resource not found',
    ) {
        parent::__construct(
            responseCode: Response::HTTP_NOT_FOUND,
            description: $description,
        );
    }
}
