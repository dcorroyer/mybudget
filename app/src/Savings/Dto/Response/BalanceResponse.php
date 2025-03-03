<?php

declare(strict_types=1);

namespace App\Savings\Dto\Response;

use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'BalanceResponse', description: 'Balance data',)]
class BalanceResponse
{
    public function __construct(
        #[OA\Property(description: 'Balance date (YYYY-MM-DD)', example: '2023-05-15', type: 'string')]
        public readonly string $date,

        #[OA\Property(description: 'Formatted date', example: 'May 15, 2023', type: 'string')]
        public readonly string $formattedDate,

        #[OA\Property(description: 'Balance amount', example: 1250.75, type: 'number', format: 'float')]
        public readonly float $balance,
    ) {
    }
}
