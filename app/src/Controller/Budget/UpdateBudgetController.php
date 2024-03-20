<?php

declare(strict_types=1);

namespace App\Controller\Budget;

use App\Dto\Budget\Payload\BudgetPayload;
use App\Dto\Budget\Payload\UpdateBudgetPayload;
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
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/budgets')]
#[OA\Tag(name: 'Budgets')]
class UpdateBudgetController extends BaseRestController
{
    #[MyOpenApi(
        httpMethod: Request::METHOD_PUT,
        operationId: 'put_budget',
        summary: 'put budget',
        responses: [
            new SuccessResponse(responseClassFqcn: Budget::class, groups: [SerializationGroups::BUDGET_UPDATE], description: 'Budget update'),
            new NotFoundResponse(description: 'Budget not found'),
        ],
        requestBodyClassFqcn: BudgetPayload::class
    )]
    #[Route('/{id}', name: 'api_budgets_update', methods: Request::METHOD_PUT)]
    public function __invoke(BudgetService $budgetService, Budget $budget, #[MapRequestPayload] UpdateBudgetPayload $updateBudgetPayload): JsonResponse
    {
        return $this->successResponse(data: $budgetService->update($updateBudgetPayload, $budget), groups: [SerializationGroups::BUDGET_UPDATE]);
    }
}
