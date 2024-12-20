<?php

declare(strict_types=1);

namespace App\Controller\Transaction;

use App\Entity\Transaction;
use App\Service\TransactionService;
use App\Shared\Api\AbstractApiController;
use App\Shared\Api\Nelmio\Attribute\NoContentResponse;
use OpenApi\Attributes\Tag;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/accounts/{accountId}/transactions')]
#[Tag(name: 'Transactions')]
class DeleteTransactionController extends AbstractApiController
{
    #[NoContentResponse(description: 'Delete a transaction')]
    #[Route('/{id}', name: __METHOD__, methods: Request::METHOD_DELETE)]
    public function __invoke(
        TransactionService $transactionService,
        int $accountId,
        Transaction $transaction
    ): JsonResponse {
        $transactionService->delete($accountId, $transaction);

        return $this->noContentResponse();
    }
}
