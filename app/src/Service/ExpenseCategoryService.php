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

        $this->expenseCategoryRepository->save($expenseCategory, true);

        return $expenseCategory;
    }
}
