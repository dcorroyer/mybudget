<?php

declare(strict_types=1);

namespace App\Dto\Expense\Payload;

use My\RestBundle\Contract\PayloadInterface;

class ExpensePayload implements PayloadInterface
{
    /**
     * @var array<int, ExpenseLinePayload>
     */
    private array $expenseLines = [];

    /**
     * @return ExpenseLinePayload[]
     */
    public function getExpenseLines(): array
    {
        return $this->expenseLines;
    }

    /**
     * @param ExpenseLinePayload[] $expenseLines
     */
    public function setExpenseLines(array $expenseLines): self
    {
        $this->expenseLines = $expenseLines;

        return $this;
    }
}
