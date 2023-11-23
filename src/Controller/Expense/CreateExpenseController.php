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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/expenses')]
#[OA\Tag(name: 'Expenses')]
class CreateExpenseController extends BaseRestController
{
    #[MyOpenApi(
        httpMethod: Request::METHOD_POST,
        operationId: 'post_expense',
        summary: 'post expense',
        responses: [
            new successResponse(
                responseClassFqcn: Expense::class,
                groups: [SerializationGroups::EXPENSE_CREATE],
                responseCode: Response::HTTP_CREATED,
                description: 'Expense creation',
            ),
        ],
        requestBodyClassFqcn: ExpensePayload::class
    )]
    #[Route('', name: 'app_expenses_create', methods: Request::METHOD_POST)]
    public function __invoke(
        ExpenseService $expenseService,
        #[MapRequestPayload] ExpensePayload $expensePayload
    ): JsonResponse {
        $expense = $expenseService->create($expensePayload);

        return $this->successResponse(
            data: $expense,
            groups: [SerializationGroups::EXPENSE_CREATE],
            status: Response::HTTP_CREATED,
        );
    }
}
