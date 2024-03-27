<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\ExpenseCategory\Payload\ExpenseCategoryPayload;
use App\Entity\ExpenseCategory;
use App\Repository\ExpenseCategoryRepository;

class ExpenseCategoryService
{
    public function __construct(
        private readonly ExpenseCategoryRepository $expenseCategoryRepository,
    ) {
    }

    public function create(ExpenseCategoryPayload $expenseCategoryPayload): ExpenseCategory
    {
        $expenseCategory = new ExpenseCategory();
        $expenseCategory->setName($expenseCategoryPayload->getName());

        $this->expenseCategoryRepository->save($expenseCategory);

        return $expenseCategory;
    }

    public function manageExpenseCategory(ExpenseCategoryPayload $expenseCategoryPayload): ExpenseCategory
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
            return $this->create($expenseCategoryPayload);
        }

        return $category;
    }
}
