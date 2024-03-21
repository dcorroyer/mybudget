<?php

declare(strict_types=1);

namespace App\Dto\Budget\Payload;

use App\Dto\Expense\Payload\ExpensePayload;
use App\Dto\Income\Payload\IncomePayload;
use App\Trait\Payload\DatePayloadTrait;
use My\RestBundle\Contract\PayloadInterface;

class BudgetPayload implements PayloadInterface
{
    // Todo: data à ajouter
//    use DatePayloadTrait;

    /**
     * @var array<int, IncomePayload>
     */
    private array $incomes = [];

    /**
     * @var array<int, ExpensePayload>
     */
    private array $expenses = [];

    /**
     * @return IncomePayload[]
     */
    public function getIncomes(): array
    {
        return $this->incomes;
    }

    /**
     * @param IncomePayload[] $incomes
     */
    public function setIncomes(array $incomes): static
    {
        $this->incomes = $incomes;

        return $this;
    }

    /**
     * @return ExpensePayload[]
     */
    public function getExpenses(): array
    {
        return $this->expenses;
    }

    /**
     * @param ExpensePayload[] $expenses
     */
    public function setExpenses(array $expenses): static
    {
        $this->expenses = $expenses;

        return $this;
    }
}
