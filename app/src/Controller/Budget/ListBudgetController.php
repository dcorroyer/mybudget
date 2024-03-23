<?php

declare(strict_types=1);

namespace App\Controller\Budget;

use App\Dto\Budget\Http\BudgetFilterQuery;
use App\Entity\Budget;
use App\Serializable\SerializationGroups;
use App\Service\BudgetService;
use My\RestBundle\Attribute\MyOpenApi\MyOpenApi;
use My\RestBundle\Attribute\MyOpenApi\Response\PaginatedSuccessResponse;
use My\RestBundle\Controller\BaseRestController;
use My\RestBundle\Dto\PaginationQueryParams;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/budgets')]
#[OA\Tag(name: 'Budgets')]
class ListBudgetController extends BaseRestController
{
    /**
     * @throws \Exception
     */
    #[MyOpenApi(
        httpMethod: Request::METHOD_GET,
        operationId: 'list_budget',
        summary: 'list budget',
        responses: [
            new PaginatedSuccessResponse(
                responseClassFqcn: Budget::class,
                groups: [SerializationGroups::BUDGET_LIST],
                description: 'Return the paginated list of budgets'
            ),
        ],
        queryParamsClassFqcn: [BudgetFilterQuery::class, PaginationQueryParams::class],
    )]
    #[Route('', name: 'api_budgets_list', methods: Request::METHOD_GET)]
    public function __invoke(
        BudgetService $budgetService,
        #[MapQueryString] ?PaginationQueryParams $paginationQueryParams = null,
        #[MapQueryString] ?BudgetFilterQuery $filter = null,
    ): JsonResponse {
        return $this->paginateResponse($budgetService->paginate($paginationQueryParams, $filter), [SerializationGroups::BUDGET_LIST]);
    }
}
