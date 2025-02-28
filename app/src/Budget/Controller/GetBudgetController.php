<?php

declare(strict_types=1);

namespace App\Budget\Controller;

use App\Budget\Service\BudgetService;
use App\Shared\Api\AbstractApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/budgets')]
class GetBudgetController extends AbstractApiController
{
    #[Route('/{id}', name: 'api_budgets_get', methods: Request::METHOD_GET)]
    public function __invoke(int $id, BudgetService $budgetService): JsonResponse
    {
        return $this->successResponse(data: $budgetService->get($id));
    }
}
