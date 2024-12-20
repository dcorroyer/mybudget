<?php

declare(strict_types=1);

namespace App\Controller\Transaction;

use App\Dto\Transaction\Payload\TransactionPayload;
use App\Dto\Transaction\Response\TransactionResponse;
use App\Entity\Transaction;
use App\Service\TransactionService;
use App\Shared\Api\AbstractApiController;
use App\Shared\Api\Nelmio\Attribute\SuccessResponse;
use OpenApi\Attributes\Tag;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/accounts/{accountId}/transactions')]
#[Tag(name: 'Transactions')]
class UpdateTransactionController extends AbstractApiController
{
    #[SuccessResponse(dataFqcn: TransactionResponse::class, description: 'Update a transaction')]
    #[Route('/{id}', name: __METHOD__, methods: Request::METHOD_PUT)]
    public function __invoke(
        int $accountId,
        TransactionService $transactionService,
        Transaction $transaction,
        #[MapRequestPayload] TransactionPayload $transactionPayload
    ): JsonResponse {
        return $this->successResponse(data: $transactionService->update($accountId, $transactionPayload, $transaction));
    }
}
