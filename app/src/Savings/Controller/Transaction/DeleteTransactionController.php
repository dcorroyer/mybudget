<?php

declare(strict_types=1);

namespace App\Savings\Controller\Transaction;

use App\Core\Api\AbstractApiController;
use App\Savings\Entity\Transaction;
use App\Savings\Service\TransactionService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/accounts/{accountId}/transactions')]
class DeleteTransactionController extends AbstractApiController
{
    #[Route('/{id}', name: 'api_transactions_delete', methods: Request::METHOD_DELETE)]
    public function __invoke(
        TransactionService $transactionService,
        int $accountId,
        Transaction $transaction
    ): JsonResponse {
        $transactionService->delete($accountId, $transaction);

        return $this->noContentResponse();
    }
}
