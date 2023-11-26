<?php

declare(strict_types=1);

namespace App\Controller\ExpenseCategory;

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
use Symfony\Component\Routing\Annotation\Route;

#[Route('/expenses-categories')]
#[OA\Tag(name: 'Expenses Categories')]
class ListExpenseCategoryController extends BaseRestController
{
    #[MyOpenApi(
        httpMethod: Request::METHOD_GET,
        operationId: 'list_expenses_categories',
        summary: 'list expenses categories',
        responses: [
            new paginatedSuccessResponse(
                responseClassFqcn: ExpenseCategory::class,
                groups: [SerializationGroups::EXPENSE_CATEGORY_LIST],
                description: 'Return the paginated list of expenses categories'
            ),
        ],
        queryParamsClassFqcn: [ExpenseCategoryFilterQuery::class, PaginationQueryParams::class],
    )]
    #[Route('', name: 'app_expenses_categories_list', methods: Request::METHOD_GET)]
    public function __invoke(
        ExpenseCategoryService $expenseCategoryService,
        #[MapQueryString] PaginationQueryParams $paginationQueryParams = null,
        #[MapQueryString] ExpenseCategoryFilterQuery $filter = null,
    ): JsonResponse {
        return $this->paginateResponse(
            $expenseCategoryService->paginate($paginationQueryParams),
            [SerializationGroups::EXPENSE_CATEGORY_LIST],
        );
    }
}
