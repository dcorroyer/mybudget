<?php

declare(strict_types=1);

namespace App\Savings\Dto\Response;

use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'AccountResponse', description: 'Account data',)]
class AccountResponse
{
    public function __construct(
        #[OA\Property(description: 'Unique account identifier', type: 'integer', example: 1)]
        public readonly int $id,

        #[OA\Property(description: 'Account name', type: 'string', example: 'Current Account')]
        public readonly string $name,

        #[OA\Property(description: 'Account type', type: 'string', example: 'savings')]
        public readonly string $type,

        #[OA\Property(description: 'Account balance', type: 'number', format: 'float', example: 2500.50)]
        public readonly float $balance,
    ) {
    }
}
