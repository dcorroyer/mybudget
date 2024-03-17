<?php

declare(strict_types=1);

namespace My\RestBundle\Attribute\MyOpenApi\Response;

use My\RestBundle\Attribute\MyOpenApi\MyOpenApiResponse;
use Symfony\Component\HttpFoundation\Response;

class NoContentResponse extends MyOpenApiResponse
{
    public function __construct(
        string $description = 'Return success response with no content',
    ) {
        parent::__construct(
            description: $description,
            responseCode: Response::HTTP_NO_CONTENT,
        );
    }
}
