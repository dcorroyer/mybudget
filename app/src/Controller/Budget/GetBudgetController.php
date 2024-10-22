<?php

declare(strict_types=1);

namespace App\Controller\Budget;

use App\Entity\Budget;
use App\Serializable\SerializationGroups;
use App\Service\BudgetService;
use My\RestBundle\Attribute\MyOpenApi\MyOpenApi;
use My\RestBundle\Attribute\MyOpenApi\Response\NotFoundResponse;
use My\RestBundle\Attribute\MyOpenApi\Response\SuccessResponse;
use My\RestBundle\Controller\BaseRestController;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/budgets')]
#[OA\Tag(name: 'Budgets')]
class GetBudgetController extends BaseRestController
{
    #[MyOpenApi(
        httpMethod: Request::METHOD_GET,
        operationId: 'get_budget',
        summary: 'get budget',
        responses: [
            new SuccessResponse(
                responseClassFqcn: Budget::class,
                groups: [SerializationGroups::BUDGET_GET],
                description: 'Budget get',
            ),
            new NotFoundResponse(description: 'Budget not found'),
        ],
    )]
    #[Route('/{id}', name: 'api_budgets_get', methods: Request::METHOD_GET)]
    public function __invoke(int $id, BudgetService $budgetService): JsonResponse
    {
        return $this->successResponse(data: $budgetService->get($id), groups: [SerializationGroups::BUDGET_GET]);
    }
}
