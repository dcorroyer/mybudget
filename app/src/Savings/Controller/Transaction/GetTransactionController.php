<?php

declare(strict_types=1);

namespace App\Savings\Controller\Transaction;

use App\Savings\Dto\Response\TransactionResponse;
use App\Savings\Service\TransactionService;
use App\Shared\Api\AbstractApiController;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/accounts/{accountId}/transactions')]
#[OA\Tag(name: 'Transactions')]
class GetTransactionController extends AbstractApiController
{
    #[Route('/{id}', name: 'api_transactions_get', methods: Request::METHOD_GET)]
    #[OA\Get(
        path: '/api/accounts/{accountId}/transactions/{id}',
        description: 'Retrieve a transaction by its ID',
        summary: 'Get a transaction',
        parameters: [
            new OA\Parameter(
                name: 'accountId',
                description: 'Account ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
            new OA\Parameter(
                name: 'id',
                description: 'Transaction ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: Response::HTTP_OK,
                description: 'Transaction successfully retrieved',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: new Model(type: TransactionResponse::class)),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(
                response: Response::HTTP_NOT_FOUND,
                description: 'Transaction not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Transaction not found'),
                        new OA\Property(property: 'code', type: 'integer', example: 404),
                    ],
                    type: 'object'
                )
            ),
        ]
    )]
    public function __invoke(int $accountId, int $id, TransactionService $transactionService): JsonResponse
    {
        return $this->successResponse(data: $transactionService->get($accountId, $id));
    }
}
