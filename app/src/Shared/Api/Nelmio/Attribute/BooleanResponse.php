<?php

declare(strict_types=1);

namespace App\Shared\Api\Nelmio\Attribute;

use App\Shared\Api\Dto\Meta\PaginationMeta;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Response;
use OpenApi\Generator;

#[\Attribute(\Attribute::TARGET_METHOD)]
class BooleanResponse extends Response
{
    public function __construct(
        string $description,
        bool $paginated = false,
        ?string $metaFqcn = null,
        int $statusCode = 200,
    ) {
        $properties = [new Property(property: 'success', type: 'boolean', example: true)];

        $properties[] = new Property(
            property: 'data',
            title: 'Data',
            description: $description,
            type: 'boolean',
            example: true
        );

        if ($metaFqcn !== null && class_exists($metaFqcn) === false) {
            throw new \InvalidArgumentException(\sprintf('Class %s does not exist', $metaFqcn));
        }

        if ($paginated && $metaFqcn === null) {
            $metaFqcn = PaginationMeta::class;
        }

        $properties[] = new Property(
            property: 'meta',
            ref: $metaFqcn !== null ? new Model(type: $metaFqcn) : null,
            description: 'If example is null, it means that there is no meta data',
            type: 'object',
            example: $metaFqcn !== null ? Generator::UNDEFINED : null,
            nullable: $metaFqcn !== null,
        );

        parent::__construct(
            response: $statusCode,
            description: $description,
            content: new JsonContent(properties: $properties),
        );
    }
}
