<?php

declare(strict_types=1);

namespace App\Transaction\Controller;

use App\Shared\Api\AbstractApiController;
use App\Shared\Api\Nelmio\Attribute\SuccessResponse;
use App\Transaction\Dto\Payload\TransactionPayload;
use App\Transaction\Dto\Response\TransactionResponse;
use App\Transaction\Service\TransactionService;
use OpenApi\Attributes\Tag;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/accounts/{accountId}/transactions')]
#[Tag(name: 'Transactions')]
class CreateTransactionController extends AbstractApiController
{
    #[SuccessResponse(dataFqcn: TransactionResponse::class, description: 'Create a transaction')]
    #[Route('', name: __METHOD__, methods: Request::METHOD_POST)]
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
