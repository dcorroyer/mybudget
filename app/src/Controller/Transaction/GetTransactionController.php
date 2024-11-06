<?php

declare(strict_types=1);

namespace App\Controller\Transaction;

use App\Entity\Transaction;
use App\Service\TransactionService;
use My\RestBundle\Attribute\MyOpenApi\MyOpenApi;
use My\RestBundle\Attribute\MyOpenApi\Response\NotFoundResponse;
use My\RestBundle\Attribute\MyOpenApi\Response\SuccessResponse;
use My\RestBundle\Controller\BaseRestController;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/accounts/{accountId}/transactions')]
#[OA\Tag(name: 'Transactions')]
class GetTransactionController extends BaseRestController
{
    #[MyOpenApi(
        httpMethod: Request::METHOD_GET,
        operationId: 'get_transaction',
        summary: 'get transaction',
        responses: [
            new SuccessResponse(responseClassFqcn: Transaction::class, description: 'Transaction get'),
            new NotFoundResponse(description: 'Transaction not found'),
        ],
    )]
    #[Route('/{id}', name: 'api_transactions_get', methods: Request::METHOD_GET)]
    public function __invoke(int $accountId, int $id, TransactionService $transactionService): JsonResponse
    {
        return $this->successResponse(data: $transactionService->get($accountId, $id));
    }
}
