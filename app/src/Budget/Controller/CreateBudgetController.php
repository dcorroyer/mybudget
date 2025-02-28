<?php

declare(strict_types=1);

namespace App\Budget\Controller;

use App\Budget\Dto\Payload\BudgetPayload;
use App\Budget\Service\BudgetService;
use App\Shared\Api\AbstractApiController;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/budgets')]
#[OA\Tag(name: 'Budgets')]
class CreateBudgetController extends AbstractApiController
{
    #[Route('', name: 'api_budgets_create', methods: Request::METHOD_POST)]
    #[OA\Post(
        path: '/api/budgets',
        description: 'Create a new budget',
        summary: 'Create a budget'
    )]
    #[OA\RequestBody(
        description: 'Budget data to create',
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'date', type: 'string', example: '2025-05-01', description: 'Budget date (YYYY-MM-DD)'),
                new OA\Property(
                    property: 'incomes',
                    type: 'array',
                    items: new OA\Items(
                        properties: [
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
                            new OA\Property(property: 'name', type: 'string', example: 'Loyer'),
                            new OA\Property(property: 'amount', type: 'number', format: 'float', example: 800),
                            new OA\Property(property: 'category', type: 'string', example: 'Habitation')
                        ],
                        type: 'object'
                    )
                )
            ],
            required: ['date', 'incomes', 'expenses']
        )
    )]
    #[OA\Response(
        response: Response::HTTP_CREATED,
        description: 'Budget successfully created',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: 'data',
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
            ],
            type: 'object'
        )
    )]
    #[OA\Response(
        response: Response::HTTP_BAD_REQUEST,
        description: 'Invalid data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'Validation failed'),
                new OA\Property(property: 'code', type: 'integer', example: 400)
            ],
            type: 'object'
        )
    )]
    public function __invoke(
        BudgetService $budgetService,
        #[MapRequestPayload] BudgetPayload $budgetPayload
    ): JsonResponse {
        return $this->successResponse(
            data: $budgetService->create($budgetPayload),
            status: Response::HTTP_CREATED,
        );
    }
}
