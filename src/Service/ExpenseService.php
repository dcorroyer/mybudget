<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\Expense\Payload\ExpensePayload;
use App\Dto\Expense\Response\CategoryResponse;
use App\Dto\Expense\Response\ExpenseLineResponse;
use App\Dto\Expense\Response\ExpenseResponse;
use App\Entity\Expense;
use App\Entity\ExpenseLine;
use App\Repository\CategoryRepository;
use App\Repository\ExpenseRepository;

class ExpenseService
{
    public function __construct(
        private readonly ExpenseRepository $expenseRepository,
        private readonly CategoryRepository $categoryRepository,
        private readonly CategoryService $categoryService,
    ) {
    }

    public function create(ExpensePayload $payload): ExpenseResponse
    {
        $expenseLines = [];
        $expenseLinesResponse = [];

        foreach ($payload->getExpenseLines() as $expenseLinePayload) {
            $categoryName = $expenseLinePayload->getCategory()
                ->getName();
            $existingCategory = $this->categoryRepository->findOneBy([
                'name' => $categoryName,
            ]);

            $category = ($existingCategory === null)
                ? $this->categoryService->create($expenseLinePayload->getCategory(), true)
                : $existingCategory;

            $expenseLine = new ExpenseLine();
            $expenseLine->setName($expenseLinePayload->getName())
                ->setAmount($expenseLinePayload->getAmount())
                ->setCategory($category);

            $expenseLines[] = $expenseLine;
        }

        $expense = new Expense();
        $expense->setDate($payload->getDate())
            ->setExpenseLines($expenseLines);

        $this->expenseRepository->save($expense, true);

        foreach ($expense->getExpenseLines() as $expenseLine) {
            $expenseLinesResponse[] = (new ExpenseLineResponse())
                ->setId($expenseLine->getId())
                ->setName($expenseLine->getName())
                ->setAmount($expenseLine->getAmount())
                ->setCategory((new CategoryResponse())
                    ->setId($expenseLine->getCategory()->getId())
                    ->setName($expenseLine->getCategory()->getName()))
            ;
        }

        return (new ExpenseResponse())
            ->setId($expense->getId())
            ->setDate($expense->getDate())
            ->setAmount($expense->getAmount())
            ->setExpenseLines($expenseLinesResponse)
        ;
    }
}
