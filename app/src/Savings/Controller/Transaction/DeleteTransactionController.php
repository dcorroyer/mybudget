<?php

declare(strict_types=1);

namespace App\Savings\Controller\Transaction;

use App\Savings\Entity\Transaction;
use App\Savings\Service\TransactionService;
use App\Shared\Api\AbstractApiController;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/accounts/{accountId}/transactions')]
#[OA\Tag(name: 'Transactions')]
class DeleteTransactionController extends AbstractApiController
{
    #[Route('/{id}', name: 'api_transactions_delete', methods: Request::METHOD_DELETE)]
    #[OA\Delete(
        path: '/api/accounts/{accountId}/transactions/{id}',
        description: 'Delete an existing transaction',
        summary: 'Delete a transaction'
    )]
    #[OA\Parameter(
        name: 'accountId',
        description: 'Account ID',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Parameter(
        name: 'id',
        description: 'Transaction ID',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Response(response: Response::HTTP_NO_CONTENT, description: 'Transaction successfully deleted')]
    #[OA\Response(
        response: Response::HTTP_NOT_FOUND,
        description: 'Transaction not found',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'Transaction not found'),
                new OA\Property(property: 'code', type: 'integer', example: 404),
            ],
            type: 'object'
        )
    )]
    public function __invoke(
        TransactionService $transactionService,
        int $accountId,
        Transaction $transaction
    ): JsonResponse {
        $transactionService->delete($accountId, $transaction);

        return $this->noContentResponse();
    }
}
