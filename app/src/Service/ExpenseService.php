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
        private readonly ExpenseCategoryService $expenseCategoryService
    ) {
    }

    public function create(ExpensePayload $expensePayload, Budget $budget): Expense
    {
        $expense = new Expense();

        $category = $this->expenseCategoryService->get($expensePayload->expenseCategoryId);

        $expense->setName($expensePayload->name)
            ->setAmount($expensePayload->amount)
            ->setExpenseCategory($category)
            ->setBudget($budget)
        ;

        $this->expenseRepository->save($expense);

        return $expense;
    }
}
