<?php

declare(strict_types=1);

namespace App\Budget\Controller;

use App\Budget\Dto\Response\BudgetResponse;
use App\Budget\Service\BudgetService;
use App\Shared\Api\AbstractApiController;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/budgets')]
#[OA\Tag(name: 'Budgets')]
class GetBudgetController extends AbstractApiController
{
    #[Route('/{id}', name: 'api_budgets_get', methods: Request::METHOD_GET)]
    #[OA\Get(
        path: '/api/budgets/{id}',
        description: 'Retrieve a budget by its ID',
        summary: 'Get a budget',
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Budget ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: Response::HTTP_OK,
                description: 'Budget successfully retrieved',
                content: new OA\JsonContent(
                    properties: [new OA\Property(property: 'data', ref: new Model(type: BudgetResponse::class))],
                    type: 'object'
                )
            ),
            new OA\Response(
                response: Response::HTTP_NOT_FOUND,
                description: 'Budget not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Budget not found'),
                        new OA\Property(property: 'code', type: 'integer', example: 404),
                    ],
                    type: 'object'
                )
            ),
        ]
    )]
    public function __invoke(int $id, BudgetService $budgetService): JsonResponse
    {
        return $this->successResponse(data: $budgetService->get($id));
    }
}
