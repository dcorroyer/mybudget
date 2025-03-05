<?php

declare(strict_types=1);

namespace App\Budget\Dto\Response;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'BudgetResponse', description: 'Budget data',)]
class BudgetResponse
{
    public function __construct(
        #[OA\Property(description: 'Unique budget identifier', type: 'integer', example: 1)]
        public readonly int $id,

        #[OA\Property(description: 'Budget name', type: 'string', example: 'Budget 2023-05')]
        public readonly string $name,

        #[OA\Property(description: 'Total income amount', type: 'number', format: 'float', example: 3000)]
        public readonly float $incomesAmount,

        #[OA\Property(description: 'Total expenses amount', type: 'number', format: 'float', example: 1200)]
        public readonly float $expensesAmount,

        #[OA\Property(description: 'Saving capacity', type: 'number', format: 'float', example: 1800)]
        public readonly float $savingCapacity,

        #[OA\Property(description: 'Budget date (YYYY-MM)', type: 'string', example: '2023-05')]
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
