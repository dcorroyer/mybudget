<?php

declare(strict_types=1);

namespace App\Controller\Budget;

use App\Dto\Budget\Payload\BudgetPayload;
use App\Service\BudgetService;
use App\Core\Api\AbstractApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/budgets')]
class CreateBudgetController extends AbstractApiController
{
    #[Route('', name: 'api_budgets_create', methods: Request::METHOD_POST)]
    public function __invoke(
        BudgetService $budgetService,
        #[MapRequestPayload] BudgetPayload $budgetPayload
    ): JsonResponse {
        return $this->successResponse(
            data: $budgetService->create($budgetPayload),
            status: Response::HTTP_CREATED,
        );
    }
}
