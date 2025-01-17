<?php

declare(strict_types=1);

namespace App\Controller\Transaction;

use App\Core\Api\AbstractApiController;
use App\Dto\Transaction\Payload\TransactionPayload;
use App\Entity\Transaction;
use App\Service\TransactionService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/accounts/{accountId}/transactions')]
class UpdateTransactionController extends AbstractApiController
{
    #[Route('/{id}', name: 'api_transaction_update', methods: Request::METHOD_PUT)]
    public function __invoke(
        int $accountId,
        TransactionService $transactionService,
        Transaction $transaction,
        #[MapRequestPayload] TransactionPayload $transactionPayload
    ): JsonResponse {
        return $this->successResponse(data: $transactionService->update($accountId, $transactionPayload, $transaction));
    }
}
