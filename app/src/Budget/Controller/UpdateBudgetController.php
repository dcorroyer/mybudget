<?php

declare(strict_types=1);

namespace App\Budget\Controller;

use App\Budget\Dto\Payload\BudgetPayload;
use App\Budget\Dto\Response\BudgetResponse;
use App\Budget\Entity\Budget;
use App\Budget\Service\BudgetService;
use App\Shared\Api\AbstractApiController;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/budgets')]
#[OA\Tag(name: 'Budgets')]
class UpdateBudgetController extends AbstractApiController
{
    #[Route('/{id}', name: 'api_budgets_update', methods: Request::METHOD_PUT)]
    #[OA\Put(
        path: '/api/budgets/{id}',
        description: 'Update an existing budget',
        summary: 'Update a budget',
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Budget ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        requestBody: new OA\RequestBody(
            description: 'Budget data to update',
            required: true,
            content: new OA\JsonContent(ref: new Model(type: BudgetPayload::class))
        ),
        responses: [
            new OA\Response(
                response: Response::HTTP_OK,
                description: 'Budget successfully updated',
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
            new OA\Response(
                response: Response::HTTP_BAD_REQUEST,
                description: 'Invalid data',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Validation failed'),
                        new OA\Property(property: 'code', type: 'integer', example: 400),
                    ],
                    type: 'object'
                )
            ),
        ]
    )]
    public function __invoke(
        BudgetService $budgetService,
        Budget $budget,
        #[MapRequestPayload] BudgetPayload $budgetPayload
    ): JsonResponse {
        return $this->successResponse(data: $budgetService->update($budgetPayload, $budget));
    }
}
