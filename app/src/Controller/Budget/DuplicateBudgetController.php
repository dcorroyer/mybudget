<?php

declare(strict_types=1);

namespace App\Controller\Budget;

use App\Entity\Budget;
use App\Service\BudgetService;
use My\RestBundle\Attribute\MyOpenApi\MyOpenApi;
use My\RestBundle\Attribute\MyOpenApi\Response\SuccessResponse;
use My\RestBundle\Controller\BaseRestController;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/budgets')]
#[OA\Tag(name: 'Budgets')]
class DuplicateBudgetController extends BaseRestController
{
    #[MyOpenApi(
        httpMethod: Request::METHOD_POST,
        operationId: 'duplicate_budget',
        summary: 'duplicate budget',
        responses: [
            new SuccessResponse(
                responseClassFqcn: Budget::class,
                responseCode: Response::HTTP_CREATED,
                description: 'Budget duplication',
            ),
        ],
    )]
    #[Route('/duplicate/{id}', name: 'api_budgets_duplicate', methods: Request::METHOD_POST)]
    public function __invoke(BudgetService $budgetService, ?int $id = null): JsonResponse
    {
        return $this->successResponse(data: $budgetService->duplicate($id), status: Response::HTTP_CREATED);
    }
}
