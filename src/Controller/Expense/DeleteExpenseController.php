<?php

declare(strict_types=1);

namespace App\Controller\Expense;

use App\Entity\Expense;
use App\Serializable\SerializationGroups;
use App\Service\ExpenseService;
use My\RestBundle\Attribute\MyOpenApi\MyOpenApi;
use My\RestBundle\Attribute\MyOpenApi\Response\NotFoundResponse;
use My\RestBundle\Attribute\MyOpenApi\Response\SuccessResponse;
use My\RestBundle\Controller\BaseRestController;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/expenses')]
#[OA\Tag(name: 'Expenses')]
class DeleteExpenseController extends BaseRestController
{
    #[MyOpenApi(
        httpMethod: Request::METHOD_DELETE,
        operationId: 'delete_expense',
        summary: 'delete expense',
        responses: [
            new successResponse(
                responseClassFqcn: Expense::class,
                groups: [SerializationGroups::EXPENSE_DELETE],
                description: 'Expense delete',
            ),
            new NotFoundResponse(description: 'Expense not found'),
        ],
    )]
    #[Route('/{id}', name: 'app_expenses_delete', methods: Request::METHOD_DELETE)]
    public function delete(ExpenseService $expenseService, Expense $expense): Response
    {
        $expenseService->delete($expense);

        return $this->createNoContentResponse();
    }
}
