<?php

declare(strict_types=1);

namespace App\Savings\Dto\Response;

use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'AccountResponse', description: 'Account data',)]
class AccountResponse
{
    public function __construct(
        #[OA\Property(description: 'Unique account identifier', example: 1, type: 'integer')]
        public readonly int $id,

        #[OA\Property(description: 'Account name', example: 'Current Account', type: 'string')]
        public readonly string $name,

        #[OA\Property(description: 'Account type', example: 'savings', type: 'string')]
        public readonly string $type,

        #[OA\Property(description: 'Account balance', example: 2500.50, type: 'number', format: 'float')]
        public readonly float $balance,
    ) {
    }
}
