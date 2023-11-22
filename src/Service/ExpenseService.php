<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\Expense\Http\ExpenseFilterQuery;
use App\Dto\Expense\Payload\ExpensePayload;
use App\Dto\Expense\Response\CategoryResponse;
use App\Dto\Expense\Response\ExpenseLineResponse;
use App\Dto\Expense\Response\ExpenseResponse;
use App\Entity\Expense;
use App\Entity\ExpenseLine;
use App\Repository\CategoryRepository;
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
        private readonly CategoryRepository $categoryRepository,
        private readonly CategoryService $categoryService,
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
        $expenseLines = [];
        $expenseLinesResponse = [];

        foreach ($payload->getExpenseLines() as $expenseLinePayload) {
            $category = $expenseLinePayload->getCategory()
                ->getId() !== null
                ? $this->categoryRepository->find($expenseLinePayload->getCategory()->getId())
                : $this->categoryService->create($expenseLinePayload->getCategory());

            if ($category === null) {
                throw new \InvalidArgumentException('Category not found');
            }

            $expenseLine = $expenseLinePayload->getId() !== null
                ? $this->expenseLineRepository->find($expenseLinePayload->getId())
                : new ExpenseLine();

            $expenseLine->setName($expenseLinePayload->getName())
                ->setAmount($expenseLinePayload->getAmount())
                ->setCategory($category);

            $expenseLines[] = $expenseLine;
        }

        $expense->setDate($payload->getDate());

        foreach ($expenseLines as $expenseLine) {
            $expense->addExpenseLine($expenseLine);
        }

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
