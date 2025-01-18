<?php

declare(strict_types=1);

namespace App\Budget\Service;

use App\Budget\Dto\Payload\ExpensePayload;
use App\Budget\Entity\Budget;
use App\Budget\Entity\Expense;
use App\Budget\Repository\ExpenseRepository;

class ExpenseService
{
    public function __construct(
        private readonly ExpenseRepository $expenseRepository,
    ) {
    }

    public function create(ExpensePayload $expensePayload, Budget $budget): Expense
    {
        $expense = new Expense();

        $expense->setName($expensePayload->name)
            ->setAmount($expensePayload->amount)
            ->setCategory($expensePayload->category)
            ->setBudget($budget)
        ;

        $this->expenseRepository->save($expense);

        return $expense;
    }
}
