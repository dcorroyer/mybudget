<?php

declare(strict_types=1);

namespace App\Budget\Dto\Response;

use My\RestBundle\Contract\ResponseInterface;

class BudgetResponse implements ResponseInterface
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly float $incomesAmount,
        public readonly float $expensesAmount,
        public readonly float $savingCapacity,
        public readonly string $date,
        /** @var IncomeResponse[] */
        public readonly array $incomes,
        /** @var ExpenseResponse[] */
        public readonly array $expenses,
    ) {
    }
}
