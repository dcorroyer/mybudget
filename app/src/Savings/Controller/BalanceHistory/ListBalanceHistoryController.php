<?php

declare(strict_types=1);

namespace App\Savings\Controller\BalanceHistory;

use App\Savings\Dto\Http\BalanceHistoryFilterQuery;
use App\Savings\Service\BalanceHistoryService;
use App\Shared\Api\AbstractApiController;
use App\Shared\Enum\PeriodsEnum;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/accounts/balance-history', priority: 10)]
#[OA\Tag(name: 'Balance History')]
class ListBalanceHistoryController extends AbstractApiController
{
    #[Route('', name: 'api_balance_history', methods: Request::METHOD_GET)]
    #[OA\Get(
        path: '/api/accounts/balance-history',
        description: 'Get the monthly balance history',
        summary: 'Balance history'
    )]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Balance history successfully retrieved',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: 'data',
                    properties: [
                        new OA\Property(
                            property: 'accounts',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'id', type: 'integer', example: 1),
                                    new OA\Property(property: 'name', type: 'string', example: 'Savings Account')
                                ],
                                type: 'object'
                            )
                        ),
                        new OA\Property(
                            property: 'balances',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'date', type: 'string', example: '2023-01'),
                                    new OA\Property(property: 'formattedDate', type: 'string', example: 'January 2023'),
                                    new OA\Property(property: 'balance', type: 'number', format: 'float', example: 2500.50)
                                ],
                                type: 'object'
                            )
                        )
                    ],
                    type: 'object'
                )
            ],
            type: 'object'
        )
    )]
    public function __invoke(
        BalanceHistoryService $balanceHistoryService,
        #[MapQueryString] ?BalanceHistoryFilterQuery $filter = null,
    ): JsonResponse {
        return $this->successResponse(
            data: $balanceHistoryService->getMonthlyBalanceHistory($filter?->getAccountIds(), $filter?->getPeriod())
        );
    }
}
