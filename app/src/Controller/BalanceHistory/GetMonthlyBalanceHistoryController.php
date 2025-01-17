<?php

declare(strict_types=1);

namespace App\Controller\BalanceHistory;

use App\Core\Api\AbstractApiController;
use App\Dto\BalanceHistory\Http\BalanceHistoryFilterQuery;
use App\Service\BalanceHistoryService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/accounts/balance-history', priority: 10)]
class GetMonthlyBalanceHistoryController extends AbstractApiController
{
    #[Route('', name: 'api_balance_history', methods: Request::METHOD_GET)]
    public function __invoke(
        BalanceHistoryService $balanceHistoryService,
        #[MapQueryString] ?BalanceHistoryFilterQuery $filter = null,
    ): JsonResponse {
        return $this->successResponse(
            data: $balanceHistoryService->getMonthlyBalanceHistory($filter?->getAccountIds(), $filter?->getPeriod())
        );
    }
}
