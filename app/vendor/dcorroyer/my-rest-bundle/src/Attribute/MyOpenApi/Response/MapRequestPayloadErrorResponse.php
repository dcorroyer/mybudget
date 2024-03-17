<?php

declare(strict_types=1);

namespace My\RestBundle\Attribute\MyOpenApi\Response;

use Symfony\Component\HttpFoundation\Response;
use My\RestBundle\Attribute\MyOpenApi\MyOpenApiResponseList;

/**
 * Use this ErrorResponse when you use MapRequestPayload attribute.
 */
class MapRequestPayloadErrorResponse extends MyOpenApiResponseList
{
    public function __construct()
    {
        parent::__construct([
            new ErrorResponse(responseCode: Response::HTTP_UNPROCESSABLE_ENTITY, description: 'Payload validation failed'),
            new ErrorResponse(responseCode: Response::HTTP_BAD_REQUEST, description: 'Invalid payload'),
            new ErrorResponse(responseCode: Response::HTTP_UNSUPPORTED_MEDIA_TYPE, description: 'Unsupported media type')
        ]);
    }
}
