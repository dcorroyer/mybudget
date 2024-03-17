<?php

declare(strict_types=1);

namespace My\RestBundle\Attribute\MyOpenApi\Response;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;
use My\RestBundle\Attribute\MyOpenApi\MyOpenApiResponseList;

/**
 * Use this ErrorResponse when you use MapRequestPayload attribute.
 */
class MapQueryStringErrorResponse extends MyOpenApiResponseList
{
    public function __construct()
    {
        parent::__construct([
            new OA\Response(
                response: Response::HTTP_NOT_FOUND,
                description: 'Invalid query string',
            ),
        ]);
    }
}
