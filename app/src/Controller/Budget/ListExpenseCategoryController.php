<?php

declare(strict_types=1);

namespace App\Controller\Budget;

use App\Dto\ExpenseCategory\Http\ExpenseCategoryFilterQuery;
use App\Entity\ExpenseCategory;
use App\Serializable\SerializationGroups;
use App\Service\ExpenseCategoryService;
use My\RestBundle\Attribute\MyOpenApi\MyOpenApi;
use My\RestBundle\Attribute\MyOpenApi\Response\PaginatedSuccessResponse;
use My\RestBundle\Controller\BaseRestController;
use My\RestBundle\Dto\PaginationQueryParams;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/expense-categories')]
#[OA\Tag(name: 'Expense Categories')]
class ListExpenseCategoryController extends BaseRestController
{
    #[MyOpenApi(
        httpMethod: Request::METHOD_GET,
        operationId: 'list_expense_category',
        summary: 'list expense category',
        responses: [
            new PaginatedSuccessResponse(
                responseClassFqcn: ExpenseCategory::class,
                groups: [SerializationGroups::EXPENSE_CATEGORY_LIST],
                description: 'Return the paginated list of expense categories'
            ),
        ],
        queryParamsClassFqcn: [ExpenseCategoryFilterQuery::class, PaginationQueryParams::class],
    )]
    #[Route('', name: 'api_expense_categories_list', methods: Request::METHOD_GET)]
    public function __invoke(
        ExpenseCategoryService $expenseCategoryService,
        #[MapQueryString] ?PaginationQueryParams $paginationQueryParams = null,
        #[MapQueryString] ?ExpenseCategoryFilterQuery $filter = null,
    ): JsonResponse {
        return $this->paginateResponse(
            $expenseCategoryService->paginate($paginationQueryParams),
            [SerializationGroups::EXPENSE_CATEGORY_LIST]
        );
    }
}
