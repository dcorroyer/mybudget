<?php

declare(strict_types=1);

namespace App\Savings\Controller;

use App\Savings\Dto\Http\BalanceHistoryFilterQuery;
use App\Savings\Dto\Response\BalanceHistoryResponse;
use App\Savings\Service\BalanceHistoryService;
use App\Shared\Api\AbstractApiController;
use App\Shared\Api\Nelmio\Attribute\SuccessResponse;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/accounts/balance-history', priority: 10)]
#[OA\Tag(name: 'Balance History')]
class GetMonthlyBalanceHistoryController extends AbstractApiController
{
    #[SuccessResponse(dataFqcn: BalanceHistoryResponse::class, description: 'Get monthly balance history')]
    #[Route('', name: __METHOD__, methods: Request::METHOD_GET)]
    public function __invoke(
        BalanceHistoryService $balanceHistoryService,
        #[MapQueryString] ?BalanceHistoryFilterQuery $filter = null,
    ): JsonResponse {
        return $this->successResponse(
            data: $balanceHistoryService->getMonthlyBalanceHistory($filter?->getAccountIds(), $filter?->getPeriod())
        );
    }
}
