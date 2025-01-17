<?php

declare(strict_types=1);

namespace App\Savings\Controller\Transaction;

use App\Core\Api\AbstractApiController;
use App\Savings\Dto\Transaction\Payload\TransactionPayload;
use App\Savings\Service\TransactionService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/accounts/{accountId}/transactions')]
class CreateTransactionController extends AbstractApiController
{
    #[Route('', name: 'api_transactions_create', methods: Request::METHOD_POST)]
    public function __invoke(
        int $accountId,
        TransactionService $transactionService,
        #[MapRequestPayload] TransactionPayload $transactionPayload
    ): JsonResponse {
        return $this->successResponse(
            data: $transactionService->create($accountId, $transactionPayload),
            status: Response::HTTP_CREATED,
        );
    }
}
