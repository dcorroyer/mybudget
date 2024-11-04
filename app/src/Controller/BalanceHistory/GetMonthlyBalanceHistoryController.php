<?php

declare(strict_types=1);

namespace App\Controller\BalanceHistory;

use App\Dto\BalanceHistory\Http\BalanceHistoryFilterQuery;
use App\Entity\BalanceHistory;
use App\Service\BalanceHistoryService;
use My\RestBundle\Attribute\MyOpenApi\MyOpenApi;
use My\RestBundle\Attribute\MyOpenApi\Response\SuccessResponse;
use My\RestBundle\Controller\BaseRestController;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/accounts/balance-history', priority: 10)]
#[OA\Tag(name: 'Balance History')]
class GetMonthlyBalanceHistoryController extends BaseRestController
{
    #[MyOpenApi(
        httpMethod: Request::METHOD_GET,
        operationId: 'get_balance_history',
        summary: 'Get balance history',
        responses: [
            new SuccessResponse(
                responseClassFqcn: BalanceHistory::class,
                description: 'Return the balance history',
            ),
        ],
        queryParamsClassFqcn: [BalanceHistoryFilterQuery::class],
    )]
    #[Route('', name: 'api_balance_history', methods: Request::METHOD_GET)]
    public function __invoke(
        BalanceHistoryService $balanceHistoryService,
        #[MapQueryString] ?BalanceHistoryFilterQuery $filter = null,
    ): JsonResponse {
        return $this->successResponse($balanceHistoryService->getMonthlyBalanceHistory($filter));
    }
}
