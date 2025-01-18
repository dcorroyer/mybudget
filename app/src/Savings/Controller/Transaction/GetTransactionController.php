<?php

declare(strict_types=1);

namespace App\Savings\Controller\Transaction;

use App\Core\Api\AbstractApiController;
use App\Savings\Service\TransactionService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/accounts/{accountId}/transactions')]
class GetTransactionController extends AbstractApiController
{
    #[Route('/{id}', name: 'api_transactions_get', methods: Request::METHOD_GET)]
    public function __invoke(int $accountId, int $id, TransactionService $transactionService): JsonResponse
    {
        return $this->successResponse(data: $transactionService->get($accountId, $id));
    }
}
