<?php

declare(strict_types=1);

namespace App\Budget\Controller;

use App\Budget\Entity\Budget;
use App\Budget\Service\BudgetService;
use App\Shared\Api\AbstractApiController;
use App\Shared\Api\Nelmio\Attribute\NoContentResponse;
use OpenApi\Attributes\Tag;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/budgets')]
#[Tag(name: 'Budgets')]
class DeleteBudgetController extends AbstractApiController
{
    #[NoContentResponse(description: 'Delete a budget')]
    #[Route('/{id}', name: __METHOD__, methods: Request::METHOD_DELETE)]
    public function __invoke(BudgetService $budgetService, Budget $budget): JsonResponse
    {
        $budgetService->delete($budget);

        return $this->noContentResponse();
    }
}
