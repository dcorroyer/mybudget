<?php

declare(strict_types=1);

namespace App\Controller\Transaction;

use App\Dto\Transaction\Http\TransactionFilterQuery;
use App\Entity\Transaction;
use App\Serializable\SerializationGroups;
use App\Service\TransactionService;
use My\RestBundle\Attribute\MyOpenApi\MyOpenApi;
use My\RestBundle\Attribute\MyOpenApi\Response\PaginatedSuccessResponse;
use My\RestBundle\Controller\BaseRestController;
use My\RestBundle\Dto\PaginationQueryParams;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/accounts/transactions', priority: 10)]
#[OA\Tag(name: 'Transactions')]
class ListTransactionController extends BaseRestController
{
    #[MyOpenApi(
        httpMethod: Request::METHOD_GET,
        operationId: 'list_transaction',
        summary: 'list transactions',
        responses: [
            new PaginatedSuccessResponse(
                responseClassFqcn: Transaction::class,
                groups: [SerializationGroups::TRANSACTION_LIST],
                description: 'Return the list of transactions'
            ),
        ],
        queryParamsClassFqcn: [PaginationQueryParams::class, TransactionFilterQuery::class],
    )]
    #[Route('', name: 'api_transactions_list', methods: Request::METHOD_GET)]
    public function __invoke(
        TransactionService $transactionService,
        #[MapQueryString] ?PaginationQueryParams $paginationQueryParams = null,
        #[MapQueryString] ?TransactionFilterQuery $filter = null,
    ): JsonResponse {
        return $this->paginateResponse(
            $transactionService->paginate($filter?->accountIds, $paginationQueryParams),
            [SerializationGroups::TRANSACTION_LIST]
        );
    }
}
