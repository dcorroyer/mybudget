<?php

declare(strict_types=1);

namespace App\Savings\Controller\BalanceHistory;

use App\Savings\Dto\Http\BalanceHistoryFilterQuery;
use App\Savings\Service\BalanceHistoryService;
use App\Shared\Api\AbstractApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/accounts/balance-history', priority: 10)]
class ListBalanceHistoryController extends AbstractApiController
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
