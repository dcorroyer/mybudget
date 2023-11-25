<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\ExpenseCategory\Http\ExpenseCategoryFilterQuery;
use App\Dto\ExpenseCategory\Payload\ExpenseCategoryPayload;
use App\Dto\ExpenseCategory\Response\ExpenseCategoryResponse;
use App\Entity\ExpenseCategory;
use App\Repository\ExpenseCategoryRepository;
use Doctrine\Common\Collections\Criteria;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use My\RestBundle\Dto\PaginationQueryParams;

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

    public function update(ExpenseCategoryPayload $payload, ExpenseCategory $category): ExpenseCategoryResponse
    {
        if ($category->getName() !== $payload->getName()) {
            $category->setName($payload->getName());

            $this->expenseCategoryRepository->save($category, true);
        }

        return (new ExpenseCategoryResponse())
            ->setId($category->getId())
            ->setName($category->getName());
    }

    public function paginate(
        PaginationQueryParams $paginationQueryParams = null,
        ExpenseCategoryFilterQuery $filter = null
    ): SlidingPagination {
        return $this->expenseCategoryRepository->paginate($paginationQueryParams, $filter, Criteria::create());
    }
}
