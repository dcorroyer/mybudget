<?php

declare(strict_types=1);

namespace App\Budget\Controller;

use App\Budget\Service\BudgetService;
use App\Shared\Api\AbstractApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/budgets')]
class DuplicateBudgetController extends AbstractApiController
{
    #[Route('/duplicate/{id}', name: 'api_budgets_duplicate', methods: Request::METHOD_POST)]
    public function __invoke(BudgetService $budgetService, ?int $id = null): JsonResponse
    {
        return $this->successResponse(data: $budgetService->duplicate($id), status: Response::HTTP_CREATED);
    }
}
