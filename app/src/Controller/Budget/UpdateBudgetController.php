<?php

declare(strict_types=1);

namespace App\Controller\Budget;

use App\Dto\Budget\Payload\BudgetPayload;
use App\Entity\Budget;
use App\Service\BudgetService;
use App\Core\Api\AbstractApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/budgets')]
class UpdateBudgetController extends AbstractApiController
{
    #[Route('/{id}', name: 'api_budgets_update', methods: Request::METHOD_PUT)]
    public function __invoke(
        BudgetService $budgetService,
        Budget $budget,
        #[MapRequestPayload] BudgetPayload $budgetPayload
    ): JsonResponse {
        return $this->successResponse(data: $budgetService->update($budgetPayload, $budget));
    }
}
