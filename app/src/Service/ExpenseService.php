<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\Expense\Payload\ExpenseLinePayload;
use App\Dto\Expense\Payload\ExpensePayload;
use App\Dto\ExpenseCategory\Payload\ExpenseCategoryPayload;
use App\Entity\Budget;
use App\Entity\Expense;
use App\Entity\ExpenseCategory;
use App\Repository\ExpenseCategoryRepository;
use App\Repository\ExpenseRepository;

class ExpenseService
{
    public function __construct(
        private readonly ExpenseRepository $expenseRepository,
        private readonly ExpenseCategoryRepository $expenseCategoryRepository,
        private readonly ExpenseCategoryService $expenseCategoryService,
    ) {
    }

    public function create(ExpensePayload $expensePayload): array
    {
        $expenses = [];
        $category = $this->manageExpenseCategory($expensePayload->getCategory());

        foreach ($expensePayload->getExpenseLines() as $expenseLinePayload) {
            $expense = new Expense();

            $expense->setAmount($expenseLinePayload->getAmount())
                ->setName($expenseLinePayload->getName())
                ->setExpenseCategory($category);

            $this->expenseRepository->save($expense, true);

            $expenses[] = $expense;
        }

        return $expenses;
    }

    private function manageExpenseCategory(ExpenseCategoryPayload $expenseCategoryPayload): ExpenseCategory
    {
        $category = null;
        $categoryId = $expenseCategoryPayload->getId();
        $categoryName = $expenseCategoryPayload->getName();

        if ($categoryId !== null) {
            $category = $this->expenseCategoryRepository->find($categoryId);
        }

        if ($category === null) {
            $category = $this->expenseCategoryRepository->findOneBy([
                'name' => $categoryName,
            ]);
        }

        if ($category === null) {
            return $this->expenseCategoryService->create($expenseCategoryPayload);
        }

        return $category;
    }
}
