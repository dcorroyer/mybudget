<?php

declare(strict_types=1);

namespace App\Controller\Transaction;

use App\Dto\Transaction\Response\TransactionResponse;
use App\Entity\Transaction;
use App\Service\TransactionService;
use My\RestBundle\Attribute\MyOpenApi\MyOpenApi;
use My\RestBundle\Attribute\MyOpenApi\Response\NotFoundResponse;
use My\RestBundle\Attribute\MyOpenApi\Response\SuccessResponse;
use App\Controller\BaseRestController;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/accounts/{accountId}/transactions')]
#[OA\Tag(name: 'Transactions')]
class DeleteTransactionController extends BaseRestController
{
    #[MyOpenApi(
        httpMethod: Request::METHOD_DELETE,
        operationId: 'delete_transaction',
        summary: 'delete transaction',
        responses: [
            new SuccessResponse(
                responseClassFqcn: TransactionResponse::class,
                responseCode: Response::HTTP_NO_CONTENT,
                description: 'Transaction deleted'
            ),
            new NotFoundResponse(description: 'Transaction not found'),
        ],
    )]
    #[Route('/{id}', name: 'api_transactions_delete', methods: Request::METHOD_DELETE)]
    public function __invoke(
        TransactionService $transactionService,
        int $accountId,
        Transaction $transaction
    ): JsonResponse {
        $transactionService->delete($accountId, $transaction);

        return $this->successResponse(data: [], status: Response::HTTP_NO_CONTENT);
    }
}
