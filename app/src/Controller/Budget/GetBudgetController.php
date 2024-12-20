<?php

declare(strict_types=1);

namespace App\Controller\Budget;

use App\Dto\Budget\Response\BudgetResponse;
use App\Service\BudgetService;
use App\Shared\Api\AbstractApiController;
use App\Shared\Api\Nelmio\Attribute\SuccessResponse;
use OpenApi\Attributes\Tag;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/budgets')]
#[Tag(name: 'Budgets')]
class GetBudgetController extends AbstractApiController
{
    #[SuccessResponse(dataFqcn: BudgetResponse::class, description: 'Get a budget')]
    #[Route('/{id}', name: __METHOD__, methods: Request::METHOD_GET)]
    public function __invoke(int $id, BudgetService $budgetService): JsonResponse
    {
        return $this->successResponse(data: $budgetService->get($id));
    }
}
