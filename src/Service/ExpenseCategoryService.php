<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\Expense\Payload\ExpenseCategoryPayload;
use App\Entity\ExpenseCategory;
use App\Repository\ExpenseCategoryRepository;

class ExpenseCategoryService
{
    public function __construct(
        private readonly ExpenseCategoryRepository $expenseCategoryRepository,
    ) {
    }

    public function create(ExpenseCategoryPayload $payload): ExpenseCategory
    {
        $category = new ExpenseCategory();
        $category->setName($payload->getName());

        $this->expenseCategoryRepository->save($category, true);

        return $category;
    }
}
