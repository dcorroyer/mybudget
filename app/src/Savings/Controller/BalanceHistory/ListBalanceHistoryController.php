<?php

declare(strict_types=1);

namespace App\Savings\Controller\BalanceHistory;

use App\Savings\Dto\Http\BalanceHistoryFilterQuery;
use App\Savings\Dto\Response\BalanceHistoryResponse;
use App\Savings\Service\BalanceHistoryService;
use App\Shared\Api\AbstractApiController;
use Nelmio\ApiDocBundle\Annotation\Model;
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
            properties: [new OA\Property(property: 'data', ref: new Model(type: BalanceHistoryResponse::class))],
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
