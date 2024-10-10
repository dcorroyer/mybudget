<?php

declare(strict_types=1);

namespace App\Controller\Budget;

use App\Entity\ExpenseCategory;
use App\Service\ExpenseCategoryService;
use My\RestBundle\Attribute\MyOpenApi\MyOpenApi;
use My\RestBundle\Attribute\MyOpenApi\Response\NotFoundResponse;
use My\RestBundle\Attribute\MyOpenApi\Response\SuccessResponse;
use My\RestBundle\Controller\BaseRestController;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/expense-categories')]
#[OA\Tag(name: 'Expense Categories')]
class DeleteExpenseCategoryController extends BaseRestController
{
    #[MyOpenApi(
        httpMethod: Request::METHOD_DELETE,
        operationId: 'delete_expense_category',
        summary: 'delete expense category',
        responses: [
            new SuccessResponse(
                responseClassFqcn: ExpenseCategory::class,
                responseCode: Response::HTTP_NO_CONTENT,
                description: 'Expense Category deleted'
            ),
            new NotFoundResponse(description: 'Expense Category not found'),
        ],
    )]
    #[Route('/{id}', name: 'api_expense_categories_delete', methods: Request::METHOD_DELETE)]
    public function __invoke(
        ExpenseCategoryService $expenseCategoryService,
        ExpenseCategory $expenseCategory
    ): JsonResponse {
        return $this->successResponse(
            data: $expenseCategoryService->delete($expenseCategory),
            status: Response::HTTP_NO_CONTENT
        );
    }
}
