<?php

declare(strict_types=1);

namespace App\Controller\Budget;

use App\Dto\Budget\Payload\BudgetPayload;
use App\Dto\Budget\Response\BudgetResponse;
use App\Service\BudgetService;
use App\Shared\Api\AbstractApiController;
use App\Shared\Api\Nelmio\Attribute\SuccessResponse;
use OpenApi\Attributes\Tag;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/budgets')]
#[Tag(name: 'Budgets')]
class CreateBudgetController extends AbstractApiController
{
    #[SuccessResponse(dataFqcn: BudgetResponse::class, description: 'Create a budget')]
    #[Route('', name: __METHOD__, methods: Request::METHOD_POST)]
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
