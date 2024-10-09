<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\ExpenseCategory\Http\ExpenseCategoryFilterQuery;
use App\Dto\ExpenseCategory\Payload\ExpenseCategoryPayload;
use App\Entity\ExpenseCategory;
use App\Repository\ExpenseCategoryRepository;
use Doctrine\Common\Collections\Criteria;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use My\RestBundle\Dto\PaginationQueryParams;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExpenseCategoryService
{
    public function __construct(
        private readonly ExpenseCategoryRepository $expenseCategoryRepository,
    ) {
    }

    public function create(ExpenseCategoryPayload $payload): ExpenseCategory
    {
        $category = new ExpenseCategory();
        $category->setName($payload->name);

        $this->expenseCategoryRepository->save($category, true);

        return $category;
    }

    public function get(int $id): ExpenseCategory
    {
        $category = $this->expenseCategoryRepository->find($id);

        if ($category === null) {
            throw new NotFoundHttpException('Expense category not found');
        }

        return $category;
    }

    public function delete(ExpenseCategory $expenseCategory): ExpenseCategory
    {
        $this->expenseCategoryRepository->delete($expenseCategory, true);

        return $expenseCategory;
    }

    /**
     * @return SlidingPagination<int, ExpenseCategory>
     */
    public function paginate(
        ?PaginationQueryParams $paginationQueryParams = null,
        ?ExpenseCategoryFilterQuery $expenseCategoryFilterQuery = null
    ): SlidingPagination {
        $criteria = Criteria::create();

        return $this->expenseCategoryRepository->paginate(
            $paginationQueryParams,
            $expenseCategoryFilterQuery,
            $criteria
        );
    }
}
