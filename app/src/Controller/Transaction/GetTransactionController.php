<?php

declare(strict_types=1);

namespace App\Controller\Transaction;

use App\Dto\Transaction\Response\TransactionResponse;
use App\Service\TransactionService;
use App\Shared\Api\AbstractApiController;
use App\Shared\Api\Nelmio\Attribute\SuccessResponse;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/accounts/{accountId}/transactions')]
#[OA\Tag(name: 'Transactions')]
class GetTransactionController extends AbstractApiController
{
    #[SuccessResponse(dataFqcn: TransactionResponse::class, description: 'Get a transaction')]
    #[Route('/{id}', name: __METHOD__, methods: Request::METHOD_GET)]
    public function __invoke(int $accountId, int $id, TransactionService $transactionService): JsonResponse
    {
        return $this->successResponse(data: $transactionService->get($accountId, $id));
    }
}
