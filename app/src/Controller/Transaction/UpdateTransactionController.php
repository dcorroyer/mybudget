<?php

declare(strict_types=1);

namespace App\Controller\Transaction;

use App\Dto\Transaction\Payload\TransactionPayload;
use App\Entity\Transaction;
use App\Service\TransactionService;
use My\RestBundle\Attribute\MyOpenApi\MyOpenApi;
use My\RestBundle\Attribute\MyOpenApi\Response\NotFoundResponse;
use My\RestBundle\Attribute\MyOpenApi\Response\SuccessResponse;
use My\RestBundle\Controller\BaseRestController;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/accounts/{accountId}/transactions')]
#[OA\Tag(name: 'Transactions')]
class UpdateTransactionController extends BaseRestController
{
    #[MyOpenApi(
        httpMethod: Request::METHOD_PUT,
        operationId: 'patch_transaction',
        summary: 'patch transaction',
        responses: [
            new SuccessResponse(responseClassFqcn: Transaction::class, description: 'Transaction updated'),
            new NotFoundResponse(description: 'Transaction not found'),
        ],
        requestBodyClassFqcn: TransactionPayload::class
    )]
    #[Route('/{id}', name: 'api_transaction_update', methods: Request::METHOD_PUT)]
    public function __invoke(
        int $accountId,
        TransactionService $transactionService,
        Transaction $transaction,
        #[MapRequestPayload] TransactionPayload $transactionPayload
    ): JsonResponse {
        return $this->successResponse(data: $transactionService->update($transactionPayload, $transaction));
    }
}
