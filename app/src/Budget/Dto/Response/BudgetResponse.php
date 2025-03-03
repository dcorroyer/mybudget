<?php

declare(strict_types=1);

namespace App\Budget\Dto\Response;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'BudgetResponse', description: 'Budget data',)]
class BudgetResponse
{
    public function __construct(
        #[OA\Property(description: 'Unique budget identifier', example: 1, type: 'integer')]
        public readonly int $id,

        #[OA\Property(description: 'Budget name', example: 'Budget 2023-05', type: 'string')]
        public readonly string $name,

        #[OA\Property(description: 'Total income amount', example: 3000, type: 'number', format: 'float')]
        public readonly float $incomesAmount,

        #[OA\Property(description: 'Total expenses amount', example: 1200, type: 'number', format: 'float')]
        public readonly float $expensesAmount,

        #[OA\Property(description: 'Saving capacity', example: 1800, type: 'number', format: 'float')]
        public readonly float $savingCapacity,

        #[OA\Property(description: 'Budget date (YYYY-MM)', example: '2023-05', type: 'string')]
        public readonly string $date,

        /** @var IncomeResponse[] */
        #[OA\Property(
            description: 'List of incomes',
            type: 'array',
            items: new OA\Items(ref: new Model(type: IncomeResponse::class))
        )]
        public readonly array $incomes,

        /** @var ExpenseResponse[] */
        #[OA\Property(
            description: 'List of expenses',
            type: 'array',
            items: new OA\Items(ref: new Model(type: ExpenseResponse::class))
        )]
        public readonly array $expenses,
    ) {
    }
}
