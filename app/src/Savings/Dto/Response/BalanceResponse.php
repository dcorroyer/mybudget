<?php

declare(strict_types=1);

namespace App\Savings\Dto\Response;

use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'BalanceResponse', description: 'Balance data',)]
class BalanceResponse
{
    public function __construct(
        #[OA\Property(description: 'Balance date (YYYY-MM-DD)', type: 'string', example: '2023-05-15')]
        public readonly string $date,

        #[OA\Property(description: 'Formatted date', type: 'string', example: 'May 15, 2023')]
        public readonly string $formattedDate,

        #[OA\Property(description: 'Balance amount', type: 'number', format: 'float', example: 1250.75)]
        public readonly float $balance,
    ) {
    }
}
