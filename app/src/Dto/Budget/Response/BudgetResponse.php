<?php

declare(strict_types=1);

namespace App\Dto\Budget\Response;

use My\RestBundle\Contract\ResponseInterface;

class BudgetResponse implements ResponseInterface
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly float $incomesAmount,
        public readonly float $expensesAmount,
        public readonly \DateTimeInterface $date,
        /** @var IncomeResponse[] */
        public readonly array $incomes,
        /** @var ExpenseResponse[] */
        public readonly array $expenses,
    ) {
    }
}
