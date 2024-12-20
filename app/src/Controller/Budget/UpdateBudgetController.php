<?php

declare(strict_types=1);

namespace App\Controller\Budget;

use App\Dto\Budget\Payload\BudgetPayload;
use App\Dto\Budget\Response\BudgetResponse;
use App\Entity\Budget;
use App\Service\BudgetService;
use App\Shared\Api\AbstractApiController;
use App\Shared\Api\Nelmio\Attribute\SuccessResponse;
use OpenApi\Attributes\Tag;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/budgets')]
#[Tag(name: 'Budgets')]
class UpdateBudgetController extends AbstractApiController
{
    #[SuccessResponse(dataFqcn: BudgetResponse::class, description: 'Update a budget')]
    #[Route('/{id}', name: __METHOD__, methods: Request::METHOD_PUT)]
    public function __invoke(
        BudgetService $budgetService,
        Budget $budget,
        #[MapRequestPayload] BudgetPayload $budgetPayload
    ): JsonResponse {
        return $this->successResponse(data: $budgetService->update($budgetPayload, $budget));
    }
}
