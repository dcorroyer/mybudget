<?php

declare(strict_types=1);

namespace App\Controller\Transaction;

use App\Dto\Transaction\Payload\TransactionPayload;
use App\Dto\Transaction\Response\TransactionResponse;
use App\Entity\Transaction;
use App\Service\TransactionService;
use My\RestBundle\Attribute\MyOpenApi\MyOpenApi;
use My\RestBundle\Attribute\MyOpenApi\Response\SuccessResponse;
use App\Controller\BaseRestController;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/accounts/{accountId}/transactions')]
#[OA\Tag(name: 'Transactions')]
class CreateTransactionController extends BaseRestController
{
    #[MyOpenApi(
        httpMethod: Request::METHOD_POST,
        operationId: 'post_transaction',
        summary: 'post transaction',
        responses: [
            new SuccessResponse(
                responseClassFqcn: TransactionResponse::class,
                responseCode: Response::HTTP_CREATED,
                description: 'Transaction creation',
            ),
        ],
        requestBodyClassFqcn: TransactionPayload::class
    )]
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
