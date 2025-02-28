<?php

declare(strict_types=1);

namespace App\Budget\Controller;

use App\Budget\Service\BudgetService;
use App\Shared\Api\AbstractApiController;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/budgets')]
#[OA\Tag(name: 'Budgets')]
class DuplicateBudgetController extends AbstractApiController
{
    #[Route('/duplicate/{id}', name: 'api_budgets_duplicate', methods: Request::METHOD_POST)]
    #[OA\Post(
        path: '/api/budgets/duplicate/{id}',
        description: 'Duplicate an existing budget',
        summary: 'Duplicate a budget'
    )]
    #[OA\Parameter(
        name: 'id',
        description: 'Budget ID to duplicate',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Response(
        response: Response::HTTP_CREATED,
        description: 'Budget successfully duplicated',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: 'data',
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', example: 2),
                        new OA\Property(property: 'name', type: 'string', example: 'Budget 2025-06'),
                        new OA\Property(property: 'incomesAmount', type: 'number', format: 'float', example: 3000),
                        new OA\Property(property: 'expensesAmount', type: 'number', format: 'float', example: 1200),
                        new OA\Property(property: 'savingCapacity', type: 'number', format: 'float', example: 1800),
                        new OA\Property(property: 'date', type: 'string', example: '2025-06'),
                        new OA\Property(
                            property: 'incomes',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'id', type: 'integer', example: 19),
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
                                    new OA\Property(property: 'id', type: 'integer', example: 37),
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
            ],
            type: 'object'
        )
    )]
    #[OA\Response(
        response: Response::HTTP_NOT_FOUND,
        description: 'Budget not found',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'Budget not found'),
                new OA\Property(property: 'code', type: 'integer', example: 404)
            ],
            type: 'object'
        )
    )]
    public function __invoke(BudgetService $budgetService, ?int $id = null): JsonResponse
    {
        return $this->successResponse(data: $budgetService->duplicate($id), status: Response::HTTP_CREATED);
    }
}
