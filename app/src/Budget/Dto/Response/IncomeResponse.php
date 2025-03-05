<?php

declare(strict_types=1);

namespace App\Budget\Dto\Response;

use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'IncomeResponse', description: 'Income data',)]
class IncomeResponse
{
    public function __construct(
        #[OA\Property(description: 'Unique income identifier', type: 'integer', example: 17)]
        public readonly int $id,

        #[OA\Property(description: 'Income name', type: 'string', example: 'Salary')]
        public readonly string $name,

        #[OA\Property(description: 'Income amount', type: 'number', format: 'float', example: 2500)]
        public readonly float $amount,
    ) {
    }
}
