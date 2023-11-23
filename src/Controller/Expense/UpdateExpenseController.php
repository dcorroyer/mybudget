<?php

declare(strict_types=1);

namespace App\Controller\Expense;

use App\Dto\Expense\Payload\ExpensePayload;
use App\Entity\Expense;
use App\Serializable\SerializationGroups;
use App\Service\ExpenseService;
use My\RestBundle\Attribute\MyOpenApi\MyOpenApi;
use My\RestBundle\Attribute\MyOpenApi\Response\SuccessResponse;
use My\RestBundle\Controller\BaseRestController;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/expenses')]
#[OA\Tag(name: 'Expenses')]
class UpdateExpenseController extends BaseRestController
{
    #[MyOpenApi(
        httpMethod: Request::METHOD_PUT,
        operationId: 'put_expense',
        summary: 'put expense',
        responses: [
            new successResponse(
                responseClassFqcn: Expense::class,
                groups: [SerializationGroups::EXPENSE_UPDATE],
                description: 'Expense update',
            ),
        ],
        requestBodyClassFqcn: ExpensePayload::class
    )]
    #[Route('/{id}', name: 'app_expenses_update', methods: Request::METHOD_PUT)]
    public function __invoke(
        ExpenseService $expenseService,
        #[MapRequestPayload] ExpensePayload $expensePayload,
        Expense $expense,
    ): JsonResponse {
        return $this->successResponse(
            data: $expenseService->update($expensePayload, $expense),
            groups: [SerializationGroups::EXPENSE_UPDATE],
        );
    }
}
