<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\Expense\Http\ExpenseFilterQuery;
use App\Dto\Expense\Payload\ExpensePayload;
use App\Dto\Expense\Response\ExpenseLineResponse;
use App\Dto\Expense\Response\ExpenseResponse;
use App\Dto\ExpenseCategory\Payload\ExpenseCategoryPayload;
use App\Dto\ExpenseCategory\Response\ExpenseCategoryResponse;
use App\Entity\Expense;
use App\Entity\ExpenseCategory;
use App\Entity\ExpenseLine;
use App\Repository\ExpenseCategoryRepository;
use App\Repository\ExpenseLineRepository;
use App\Repository\ExpenseRepository;
use Doctrine\Common\Collections\Criteria;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use My\RestBundle\Dto\PaginationQueryParams;

class ExpenseService
{
    public function __construct(
        private readonly ExpenseRepository $expenseRepository,
        private readonly ExpenseLineRepository $expenseLineRepository,
        private readonly ExpenseCategoryRepository $expenseCategoryRepository,
        private readonly ExpenseCategoryService $expenseCategoryService,
    ) {
    }

    public function create(ExpensePayload $payload): ExpenseResponse
    {
        $expense = new Expense();

        return $this->updateOrCreateExpense($payload, $expense);
    }

    public function update(ExpensePayload $payload, Expense $expense): ExpenseResponse
    {
        return $this->updateOrCreateExpense($payload, $expense);
    }

    public function delete(Expense $expense): Expense
    {
        $this->expenseRepository->delete($expense);

        return $expense;
    }

    public function paginate(
        PaginationQueryParams $paginationQueryParams = null,
        ExpenseFilterQuery $filter = null
    ): SlidingPagination {
        return $this->expenseRepository->paginate($paginationQueryParams, $filter, Criteria::create());
    }

    private function updateOrCreateExpense(ExpensePayload $payload, Expense $expense): ExpenseResponse
    {
        $expenseLinesResponse = [];

        foreach ($payload->getExpenseLines() as $expenseLinePayload) {
            $category = $this->manageExpenseCategory($expenseLinePayload->getCategory());

            $expenseLine = $expenseLinePayload->getId() !== null
                ? $this->expenseLineRepository->find($expenseLinePayload->getId())
                : new ExpenseLine();

            $expenseLine->setName($expenseLinePayload->getName())
                ->setAmount($expenseLinePayload->getAmount())
                ->setCategory($category);

            $expense->addExpenseLine($expenseLine);
        }

        $this->expenseRepository->save($expense, true);

        foreach ($expense->getExpenseLines() as $expenseLine) {
            $expenseLinesResponse[] = (new ExpenseLineResponse())
                ->setId($expenseLine->getId())
                ->setName($expenseLine->getName())
                ->setAmount($expenseLine->getAmount())
                ->setCategory((new ExpenseCategoryResponse())
                    ->setId($expenseLine->getCategory()->getId())
                    ->setName($expenseLine->getCategory()->getName()))
            ;
        }

        return (new ExpenseResponse())
            ->setId($expense->getId())
            ->setAmount($expense->getAmount())
            ->setExpenseLines($expenseLinesResponse)
        ;
    }

    private function manageExpenseCategory(ExpenseCategoryPayload $categoryPayload): ExpenseCategory
    {
        $category = null;
        $categoryId = $categoryPayload->getId();
        $categoryName = $categoryPayload->getName();

        if ($categoryId !== null) {
            $category = $this->expenseCategoryRepository->find($categoryId);
        }

        if ($category === null) {
            $category = $this->expenseCategoryRepository->findOneBy([
                'name' => $categoryName,
            ]);
        }

        if ($category === null) {
            $category = $this->expenseCategoryService->create($categoryPayload);
        }

        return $category;
    }
}
