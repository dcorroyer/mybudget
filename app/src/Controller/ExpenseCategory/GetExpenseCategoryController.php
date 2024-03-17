<?php

declare(strict_types=1);

namespace App\Controller\ExpenseCategory;

use Symfony\Component\Routing\Attribute\Route;
use App\Entity\ExpenseCategory;
use App\Serializable\SerializationGroups;
use My\RestBundle\Attribute\MyOpenApi\MyOpenApi;
use My\RestBundle\Attribute\MyOpenApi\Response\NotFoundResponse;
use My\RestBundle\Attribute\MyOpenApi\Response\SuccessResponse;
use My\RestBundle\Controller\BaseRestController;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

#[Route('/expenses-categories')]
#[OA\Tag(name: 'Expenses Categories')]
class GetExpenseCategoryController extends BaseRestController
{
    #[MyOpenApi(
        httpMethod: Request::METHOD_GET,
        operationId: 'get_expenses_category',
        summary: 'get expenses category',
        responses: [
            new SuccessResponse(
                responseClassFqcn: ExpenseCategory::class,
                groups: [SerializationGroups::EXPENSE_CATEGORY_GET],
                description: 'Return the expenses category'
            ),
            new NotFoundResponse(description: 'Expense category not found'),
        ],
    )]
    #[Route('/{id}', name: 'api_expenses_categories_get', methods: Request::METHOD_GET)]
    public function __invoke(ExpenseCategory $expenseCategory): JsonResponse
    {
        return $this->successResponse(data: $expenseCategory, groups: [SerializationGroups::EXPENSE_CATEGORY_GET]);
    }
}
