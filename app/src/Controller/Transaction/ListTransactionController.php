<?php

declare(strict_types=1);

namespace App\Controller\Transaction;

use App\Dto\Transaction\Http\TransactionFilterQuery;
use App\Dto\Transaction\Response\TransactionResponse;
use App\Service\TransactionService;
use App\Shared\Api\AbstractApiController;
use App\Shared\Api\Nelmio\Attribute\SuccessResponse;
use My\RestBundle\Dto\PaginationQueryParams;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/accounts/transactions', priority: 10)]
#[OA\Tag(name: 'Transactions')]
class ListTransactionController extends AbstractApiController
{
    #[SuccessResponse(dataFqcn: TransactionResponse::class, description: 'Get transactions list', paginated: true)]
    #[Route('', name: __METHOD__, methods: Request::METHOD_GET)]
    public function __invoke(
        TransactionService $transactionService,
        #[MapQueryString] ?PaginationQueryParams $paginationQueryParams = null,
        #[MapQueryString] ?TransactionFilterQuery $filter = null,
    ): JsonResponse {
        return $this->successResponse(
            data: $transactionService->paginate($filter?->getAccountIds(), $paginationQueryParams)
        );
    }
}
