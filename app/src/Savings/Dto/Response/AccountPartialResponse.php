<?php

declare(strict_types=1);

namespace App\Savings\Dto\Response;

use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'AccountPartialResponse', description: 'Account partial data',)]
class AccountPartialResponse
{
    public function __construct(
        #[OA\Property(description: 'Account identifier', type: 'integer', example: 1)]
        public readonly int $id,

        #[OA\Property(description: 'Account name', type: 'string', example: 'Current Account')]
        public readonly string $name,
    ) {
    }
}
