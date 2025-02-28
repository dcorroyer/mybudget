<?php

declare(strict_types=1);

namespace App\Savings\Controller\Transaction;

use App\Savings\Dto\Http\TransactionFilterQuery;
use App\Savings\Service\TransactionService;
use App\Shared\Api\AbstractApiController;
use App\Shared\Dto\PaginationQueryParams;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/accounts/transactions', priority: 10)]
#[OA\Tag(name: 'Transactions')]
class ListTransactionController extends AbstractApiController
{
    #[Route('', name: 'api_transactions_list', methods: Request::METHOD_GET)]
    #[OA\Get(
        path: '/api/accounts/transactions',
        description: 'Get the paginated list of transactions',
        summary: 'List transactions'
    )]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Transaction list successfully retrieved',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: 'data',
                    type: 'array',
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: 'id', type: 'integer', example: 1),
                            new OA\Property(property: 'description', type: 'string', example: 'Grocery shopping'),
                            new OA\Property(property: 'amount', type: 'number', format: 'float', example: 45.67),
                            new OA\Property(property: 'type', type: 'string', enum: ['credit', 'debit'], example: 'debit'),
                            new OA\Property(property: 'date', type: 'string', format: 'date-time', example: '2023-11-01 14:30:00'),
                            new OA\Property(
                                property: 'account',
                                properties: [
                                    new OA\Property(property: 'id', type: 'integer', example: 1),
                                    new OA\Property(property: 'name', type: 'string', example: 'Savings Account')
                                ],
                                type: 'object'
                            )
                        ],
                        type: 'object'
                    )
                ),
                new OA\Property(
                    property: 'meta',
                    properties: [
                        new OA\Property(property: 'total', type: 'integer', example: 50),
                        new OA\Property(property: 'currentPage', type: 'integer', example: 1),
                        new OA\Property(property: 'perPage', type: 'integer', example: 20),
                        new OA\Property(property: 'from', type: 'integer', example: 1),
                        new OA\Property(property: 'to', type: 'integer', example: 20),
                        new OA\Property(property: 'hasMore', type: 'boolean', example: true)
                    ],
                    type: 'object'
                )
            ],
            type: 'object'
        )
    )]
    public function __invoke(
        TransactionService $transactionService,
        #[MapQueryString] ?PaginationQueryParams $paginationQueryParams = null,
        #[MapQueryString] ?TransactionFilterQuery $filter = null,
    ): JsonResponse {
        return $this->paginatedResponse(
            pagination: $transactionService->paginate($filter?->getAccountIds(), $paginationQueryParams)
        );
    }
}
