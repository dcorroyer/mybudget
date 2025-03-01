<?php

declare(strict_types=1);

namespace App\Savings\Controller\Transaction;

use App\Savings\Dto\Payload\TransactionPayload;
use App\Savings\Dto\Response\TransactionResponse;
use App\Savings\Service\TransactionService;
use App\Shared\Api\AbstractApiController;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/accounts/{accountId}/transactions')]
#[OA\Tag(name: 'Transactions')]
class CreateTransactionController extends AbstractApiController
{
    #[Route('', name: 'api_transactions_create', methods: Request::METHOD_POST)]
    #[OA\Post(
        path: '/api/accounts/{accountId}/transactions',
        description: 'Create a new transaction for a specific account',
        summary: 'Create a transaction',
        parameters: [
            new OA\Parameter(
                name: 'accountId',
                description: 'Account ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        requestBody: new OA\RequestBody(
            description: 'Transaction data to create',
            required: true,
            content: new OA\JsonContent(ref: new Model(type: TransactionPayload::class))
        ),
        responses: [
            new OA\Response(
                response: Response::HTTP_CREATED,
                description: 'Transaction successfully created',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: new Model(type: TransactionResponse::class)),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(
                response: Response::HTTP_BAD_REQUEST,
                description: 'Invalid data',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Validation failed'),
                        new OA\Property(property: 'code', type: 'integer', example: 400),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(
                response: Response::HTTP_NOT_FOUND,
                description: 'Account not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Account not found'),
                        new OA\Property(property: 'code', type: 'integer', example: 404),
                    ],
                    type: 'object'
                )
            ),
        ]
    )]
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
