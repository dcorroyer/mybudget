<?php

declare(strict_types=1);

namespace App\Savings\Dto\Response;

use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'AccountPartialResponse', description: 'Account partial data',)]
class AccountPartialResponse
{
    public function __construct(
        #[OA\Property(description: 'Account identifier', example: 1, type: 'integer')]
        public readonly int $id,

        #[OA\Property(description: 'Account name', example: 'Current Account', type: 'string')]
        public readonly string $name,
    ) {
    }
}
