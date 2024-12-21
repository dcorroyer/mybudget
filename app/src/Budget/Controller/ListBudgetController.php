<?php

declare(strict_types=1);

namespace App\Budget\Controller;

use App\Budget\Dto\Http\BudgetFilterQuery;
use App\Budget\Dto\Response\BudgetResponse;
use App\Budget\Service\BudgetService;
use App\Shared\Api\AbstractApiController;
use App\Shared\Api\Nelmio\Attribute\SuccessResponse;
use My\RestBundle\Dto\PaginationQueryParams;
use OpenApi\Attributes\Tag;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/budgets')]
#[Tag(name: 'Budgets')]
class ListBudgetController extends AbstractApiController
{
    #[SuccessResponse(dataFqcn: BudgetResponse::class, description: 'Get the budgets list', paginated: true)]
    #[Route('', name: __METHOD__, methods: Request::METHOD_GET)]
    public function __invoke(
        BudgetService $budgetService,
        #[MapQueryString] ?PaginationQueryParams $paginationQueryParams = null,
        #[MapQueryString] ?BudgetFilterQuery $filter = null,
    ): JsonResponse {
        return $this->successResponse(data: $budgetService->paginate($paginationQueryParams, $filter));
    }
}
