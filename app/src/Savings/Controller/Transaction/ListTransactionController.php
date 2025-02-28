<?php

declare(strict_types=1);

namespace App\Savings\Controller\Transaction;

use App\Savings\Dto\Http\TransactionFilterQuery;
use App\Savings\Service\TransactionService;
use App\Shared\Api\AbstractApiController;
use App\Shared\Dto\PaginationQueryParams;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/accounts/transactions', priority: 10)]
class ListTransactionController extends AbstractApiController
{
    #[Route('', name: 'api_transactions_list', methods: Request::METHOD_GET)]
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
