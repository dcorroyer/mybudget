<?php

declare(strict_types=1);

namespace App\Budget\Dto\Response;

use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ExpenseResponse', description: 'Expense data',)]
class ExpenseResponse
{
    public function __construct(
        #[OA\Property(description: 'Unique expense identifier', example: 33, type: 'integer')]
        public readonly int $id,

        #[OA\Property(description: 'Expense name', example: 'Loyer', type: 'string')]
        public readonly string $name,

        #[OA\Property(description: 'Expense amount', example: 800, type: 'number', format: 'float')]
        public readonly float $amount,

        #[OA\Property(description: 'Expense category', example: 'Habitation', type: 'string')]
        public readonly string $category,
    ) {
    }
}
