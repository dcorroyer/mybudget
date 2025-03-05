<?php

declare(strict_types=1);

namespace App\Savings\Controller\Transaction;

use App\Savings\Dto\Http\TransactionFilterQuery;
use App\Savings\Dto\Response\TransactionResponse;
use App\Savings\Service\TransactionService;
use App\Shared\Api\AbstractApiController;
use App\Shared\Dto\PaginationQueryParams;
use Nelmio\ApiDocBundle\Annotation\Model;
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
        summary: 'List transactions',
        responses: [
            new OA\Response(
                response: Response::HTTP_OK,
                description: 'Transaction list successfully retrieved',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(ref: new Model(type: TransactionResponse::class))
                        ),
                        new OA\Property(
                            property: 'meta',
                            properties: [
                                new OA\Property(property: 'total', type: 'integer', example: 50),
                                new OA\Property(property: 'currentPage', type: 'integer', example: 1),
                                new OA\Property(property: 'perPage', type: 'integer', example: 20),
                                new OA\Property(property: 'from', type: 'integer', example: 1),
                                new OA\Property(property: 'to', type: 'integer', example: 20),
                                new OA\Property(property: 'hasMore', type: 'boolean', example: true),
                            ],
                            type: 'object'
                        ),
                    ],
                    type: 'object'
                )
            ),
        ]
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
