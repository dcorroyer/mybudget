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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/budgets')]
#[OA\Tag(name: 'Budgets')]
class DeleteBudgetController extends BaseRestController
{
    #[MyOpenApi(
        httpMethod: Request::METHOD_DELETE,
        operationId: 'delete_budget',
        summary: 'delete budget',
        responses: [
            new SuccessResponse(responseClassFqcn: Budget::class, groups: [SerializationGroups::BUDGET_DELETE], description: 'Budget delete'),
            new NotFoundResponse(description: 'Budget not found'),
        ],
    )]
    #[Route('/{id}', name: 'api_budgets_delete', methods: Request::METHOD_DELETE)]
    public function __invoke(BudgetService $budgetService, Budget $budget): Response
    {
        $budgetService->delete($budget);

        return $this->createNoContentResponse();
    }
}
