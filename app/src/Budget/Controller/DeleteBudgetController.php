<?php

declare(strict_types=1);

namespace App\Budget\Controller;

use App\Budget\Entity\Budget;
use App\Budget\Service\BudgetService;
use App\Shared\Api\AbstractApiController;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/budgets')]
#[OA\Tag(name: 'Budgets')]
class DeleteBudgetController extends AbstractApiController
{
    #[Route('/{id}', name: 'api_budgets_delete', methods: Request::METHOD_DELETE)]
    #[OA\Delete(
        path: '/api/budgets/{id}',
        description: 'Delete an existing budget',
        summary: 'Delete a budget',
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
            new OA\Response(response: Response::HTTP_NO_CONTENT, description: 'Budget successfully deleted'),
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
    public function __invoke(BudgetService $budgetService, Budget $budget): JsonResponse
    {
        $budgetService->delete($budget);

        return $this->noContentResponse();
    }
}
