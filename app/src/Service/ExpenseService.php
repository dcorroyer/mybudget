<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\Budget\Payload\Dependencies\ExpensePayload;
use App\Entity\Budget;
use App\Entity\Expense;
use App\Repository\ExpenseRepository;

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
