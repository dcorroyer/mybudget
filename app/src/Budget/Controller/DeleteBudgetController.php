<?php

declare(strict_types=1);

namespace App\Budget\Controller;

use App\Budget\Entity\Budget;
use App\Budget\Service\BudgetService;
use App\Core\Api\AbstractApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/budgets')]
class DeleteBudgetController extends AbstractApiController
{
    #[Route('/{id}', name: 'api_budgets_delete', methods: Request::METHOD_DELETE)]
    public function __invoke(BudgetService $budgetService, Budget $budget): JsonResponse
    {
        $budgetService->delete($budget);

        return $this->noContentResponse();
    }
}
