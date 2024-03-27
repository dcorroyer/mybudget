<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\Expense\Payload\ExpensePayload;
use App\Entity\Budget;
use App\Entity\Expense;
use App\Repository\ExpenseRepository;

class ExpenseService
{
    public function __construct(
        private readonly ExpenseRepository $expenseRepository,
        private readonly ExpenseCategoryService $expenseCategoryService,
    ) {
    }

    /**
     * @return array<int, Expense>
     */
    public function create(ExpensePayload $expensePayload, Budget $budget): array
    {
        $expenses = [];
        $category = $this->expenseCategoryService->manageExpenseCategory($expensePayload->getCategory());

        foreach ($expensePayload->getExpenseLines() as $expenseLinePayload) {
            $expense = new Expense();

            $expense->setAmount($expenseLinePayload->getAmount())
                ->setName($expenseLinePayload->getName())
                ->setExpenseCategory($category)
                ->setBudget($budget)
            ;

            $this->expenseRepository->save($expense);

            $expenses[] = $expense;
        }

        return $expenses;
    }
}
