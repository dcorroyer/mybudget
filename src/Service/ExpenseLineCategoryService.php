<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\Expense\Payload\ExpenseLineCategoryPayload;
use App\Entity\ExpenseLineCategory;
use App\Repository\ExpenseLineCategoryRepository;

class ExpenseLineCategoryService
{
    public function __construct(
        private readonly ExpenseLineCategoryRepository $expenseLineCategoryRepository,
    ) {
    }

    public function create(ExpenseLineCategoryPayload $payload): ExpenseLineCategory
    {
        $category = new ExpenseLineCategory();
        $category->setName($payload->getName());

        $this->expenseLineCategoryRepository->save($category, true);

        return $category;
    }
}
