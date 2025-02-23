<?php

declare(strict_types=1);

namespace App\Controller\Budget;

use App\Dto\Budget\Http\BudgetFilterQuery;
use App\Service\BudgetService;
use App\Core\Api\AbstractApiController;
use App\Core\Dto\PaginationQueryParams;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/budgets')]
class ListBudgetController extends AbstractApiController
{
    #[Route('', name: 'api_budgets_list', methods: Request::METHOD_GET)]
    public function __invoke(
        BudgetService $budgetService,
        #[MapQueryString] ?PaginationQueryParams $paginationQueryParams = null,
        #[MapQueryString] ?BudgetFilterQuery $filter = null,
    ): JsonResponse {
        return $this->paginatedResponse($budgetService->paginate($paginationQueryParams, $filter));
    }
}
