<?php

declare(strict_types=1);

namespace App\Controller\Expense;

use App\Dto\Expense\Http\ExpenseFilterQuery;
use App\Entity\Expense;
use App\Serializable\SerializationGroups;
use App\Service\ExpenseService;
use My\RestBundle\Attribute\MyOpenApi\MyOpenApi;
use My\RestBundle\Attribute\MyOpenApi\Response\PaginatedSuccessResponse;
use My\RestBundle\Controller\BaseRestController;
use My\RestBundle\Dto\PaginationQueryParams;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/expenses')]
#[OA\Tag(name: 'Expenses')]
class ListExpenseController extends BaseRestController
{
    #[MyOpenApi(
        httpMethod: Request::METHOD_GET,
        operationId: 'list_expense',
        summary: 'list expense',
        responses: [
            new paginatedSuccessResponse(
                responseClassFqcn: Expense::class,
                groups: [SerializationGroups::EXPENSE_LIST],
                description: 'Return the paginated list of expenses'
            ),
        ],
        queryParamsClassFqcn: [ExpenseFilterQuery::class, PaginationQueryParams::class],
    )]
    #[Route('', name: 'app_expenses_list', methods: Request::METHOD_GET)]
    public function __invoke(
        ExpenseService $expenseService,
        #[MapQueryString] PaginationQueryParams $paginationQueryParams = null,
        #[MapQueryString] ExpenseFilterQuery $filter = null,
    ): JsonResponse {
        return $this->paginateResponse(
            $expenseService->paginate($paginationQueryParams, $filter),
            [SerializationGroups::EXPENSE_LIST],
        );
    }
}
