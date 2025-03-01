<?php

declare(strict_types=1);

namespace App\Budget\Dto\Response;

use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'IncomeResponse', description: 'Income data',)]
class IncomeResponse
{
    public function __construct(
        #[OA\Property(description: 'Unique income identifier', example: 17, type: 'integer')]
        public readonly int $id,

        #[OA\Property(description: 'Income name', example: 'Salary', type: 'string')]
        public readonly string $name,

        #[OA\Property(description: 'Income amount', example: 2500, type: 'number', format: 'float')]
        public readonly float $amount,
    ) {
    }
}
