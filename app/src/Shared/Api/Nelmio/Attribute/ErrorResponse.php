<?php

declare(strict_types=1);

namespace App\Shared\Api\Nelmio\Attribute;

use App\Shared\Api\Dto\Dto\ApiError;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Response;

#[\Attribute(\Attribute::TARGET_METHOD)]
class ErrorResponse extends Response
{
    public function __construct(
        // TODO: Find a way to allow user to set custom default errorFqcn from config.yaml
        string $errorFqcn = ApiError::class,
        int $statusCode = 400,
    ) {
        $properties = [new Property(property: 'success', type: 'boolean', example: false)];

        $properties[] = new Property(property: 'message', type: 'string', example: 'An error occurred');

        $properties[] = new Property(property: 'code', type: 'integer', example: 92134);

        $properties[] = new Property(
            property: 'errors',
            type: 'array',
            items: new Items(ref: new Model(type: $errorFqcn)),
        );

        parent::__construct(
            response: $statusCode,
            description: "When {$statusCode} is returned",
            content: new JsonContent(properties: $properties),
        );
    }
}
