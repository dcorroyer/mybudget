<?php

declare(strict_types=1);

namespace App\Budget\Controller;

use App\Budget\Dto\Http\BudgetFilterQuery;
use App\Budget\Service\BudgetService;
use App\Shared\Api\AbstractApiController;
use App\Shared\Dto\PaginationQueryParams;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/budgets')]
#[OA\Tag(name: 'Budgets')]
class ListBudgetController extends AbstractApiController
{
    #[Route('', name: 'api_budgets_list', methods: Request::METHOD_GET)]
    #[OA\Get(
        path: '/api/budgets',
        description: 'Get a paginated list of budgets',
        summary: 'List budgets'
    )]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Budget list successfully retrieved',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: 'data',
                    type: 'array',
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: 'id', type: 'integer', example: 1),
                            new OA\Property(property: 'name', type: 'string', example: 'Budget 2025-05'),
                            new OA\Property(property: 'incomesAmount', type: 'number', format: 'float', example: 3000),
                            new OA\Property(property: 'expensesAmount', type: 'number', format: 'float', example: 1200),
                            new OA\Property(property: 'savingCapacity', type: 'number', format: 'float', example: 1800),
                            new OA\Property(property: 'date', type: 'string', example: '2025-05'),
                            new OA\Property(
                                property: 'incomes',
                                type: 'array',
                                items: new OA\Items(
                                    properties: [
                                        new OA\Property(property: 'id', type: 'integer', example: 17),
                                        new OA\Property(property: 'name', type: 'string', example: 'Salaire'),
                                        new OA\Property(property: 'amount', type: 'number', format: 'float', example: 2500)
                                    ],
                                    type: 'object'
                                )
                            ),
                            new OA\Property(
                                property: 'expenses',
                                type: 'array',
                                items: new OA\Items(
                                    properties: [
                                        new OA\Property(property: 'id', type: 'integer', example: 33),
                                        new OA\Property(property: 'name', type: 'string', example: 'Loyer'),
                                        new OA\Property(property: 'amount', type: 'number', format: 'float', example: 800),
                                        new OA\Property(property: 'category', type: 'string', example: 'Habitation')
                                    ],
                                    type: 'object'
                                )
                            )
                        ],
                        type: 'object'
                    )
                ),
                new OA\Property(
                    property: 'meta',
                    properties: [
                        new OA\Property(property: 'total', type: 'integer', example: 5),
                        new OA\Property(property: 'currentPage', type: 'integer', example: 1),
                        new OA\Property(property: 'perPage', type: 'integer', example: 20),
                        new OA\Property(property: 'from', type: 'integer', example: 1),
                        new OA\Property(property: 'to', type: 'integer', example: 5),
                        new OA\Property(property: 'hasMore', type: 'boolean', example: false)
                    ],
                    type: 'object'
                )
            ],
            type: 'object'
        )
    )]
    public function __invoke(
        BudgetService $budgetService,
        #[MapQueryString] ?PaginationQueryParams $paginationQueryParams = null,
        #[MapQueryString] ?BudgetFilterQuery $filter = null,
    ): JsonResponse {
        return $this->paginatedResponse($budgetService->paginate($filter?->getYear(), $paginationQueryParams));
    }
}
