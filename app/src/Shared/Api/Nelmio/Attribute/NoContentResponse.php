<?php

declare(strict_types=1);

namespace App\Shared\Api\Nelmio\Attribute;

use OpenApi\Attributes\Response;

#[\Attribute(\Attribute::TARGET_METHOD)]
class NoContentResponse extends Response
{
    public function __construct(
        string $description,
    ) {
        parent::__construct(
            response: 204,
            description: $description,
        );
    }
}
