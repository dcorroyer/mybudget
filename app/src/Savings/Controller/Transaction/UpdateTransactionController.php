<?php

declare(strict_types=1);

namespace App\Savings\Controller\Transaction;

use App\Savings\Dto\Payload\TransactionPayload;
use App\Savings\Entity\Transaction;
use App\Savings\Service\TransactionService;
use App\Shared\Api\AbstractApiController;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/accounts/{accountId}/transactions')]
#[OA\Tag(name: 'Transactions')]
class UpdateTransactionController extends AbstractApiController
{
    #[Route('/{id}', name: 'api_transaction_update', methods: Request::METHOD_PUT)]
    #[OA\Put(
        path: '/api/accounts/{accountId}/transactions/{id}',
        description: 'Update an existing transaction',
        summary: 'Update a transaction'
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
    #[OA\RequestBody(
        description: 'Transaction data to update',
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'description', type: 'string', example: 'Modified grocery shopping'),
                new OA\Property(property: 'amount', type: 'number', format: 'float', example: 50.20),
                new OA\Property(property: 'type', type: 'string', example: 'debit', enum: ['credit', 'debit']),
                new OA\Property(property: 'date', type: 'string', format: 'date-time', example: '2023-11-02 16:45:00')
            ]
        )
    )]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Transaction successfully updated',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: 'data',
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', example: 1),
                        new OA\Property(property: 'description', type: 'string', example: 'Modified grocery shopping'),
                        new OA\Property(property: 'amount', type: 'number', format: 'float', example: 50.20),
                        new OA\Property(property: 'type', type: 'string', enum: ['credit', 'debit'], example: 'debit'),
                        new OA\Property(property: 'date', type: 'string', format: 'date-time', example: '2023-11-02 16:45:00'),
                        new OA\Property(
                            property: 'account',
                            properties: [
                                new OA\Property(property: 'id', type: 'integer', example: 1),
                                new OA\Property(property: 'name', type: 'string', example: 'Savings Account')
                            ],
                            type: 'object'
                        )
                    ],
                    type: 'object'
                )
            ],
            type: 'object'
        )
    )]
    #[OA\Response(
        response: Response::HTTP_NOT_FOUND,
        description: 'Transaction not found',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'Transaction not found'),
                new OA\Property(property: 'code', type: 'integer', example: 404)
            ],
            type: 'object'
        )
    )]
    #[OA\Response(
        response: Response::HTTP_BAD_REQUEST,
        description: 'Invalid data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'Validation failed'),
                new OA\Property(property: 'code', type: 'integer', example: 400)
            ],
            type: 'object'
        )
    )]
    public function __invoke(
        int $accountId,
        TransactionService $transactionService,
        Transaction $transaction,
        #[MapRequestPayload] TransactionPayload $transactionPayload
    ): JsonResponse {
        return $this->successResponse(data: $transactionService->update($accountId, $transactionPayload, $transaction));
    }
}
