<?php

declare(strict_types=1);

namespace App\Controller\ExpenseCategory;

use App\Dto\ExpenseCategory\Payload\ExpenseCategoryPayload;
use App\Entity\ExpenseCategory;
use App\Serializable\SerializationGroups;
use App\Service\ExpenseCategoryService;
use My\RestBundle\Attribute\MyOpenApi\MyOpenApi;
use My\RestBundle\Attribute\MyOpenApi\Response\SuccessResponse;
use My\RestBundle\Controller\BaseRestController;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/expenses-categories')]
#[OA\Tag(name: 'Expenses Categories')]
class UpdateExpenseCategoryController extends BaseRestController
{
    #[MyOpenApi(
        httpMethod: Request::METHOD_PUT,
        operationId: 'put_expenses_category',
        summary: 'put expenses category',
        responses: [
            new successResponse(
                responseClassFqcn: ExpenseCategory::class,
                groups: [SerializationGroups::EXPENSE_CATEGORY_UPDATE],
                description: 'Expenses category update',
            ),
        ],
        requestBodyClassFqcn: ExpenseCategoryPayload::class
    )]
    #[Route('/{id}', name: 'app_expenses_categories_update', methods: Request::METHOD_PUT)]
    public function __invoke(
        ExpenseCategoryService $expenseCategoryService,
        #[MapRequestPayload] ExpenseCategoryPayload $expenseCategoryPayload,
        ExpenseCategory $expenseCategory,
    ): JsonResponse {
        return $this->successResponse(
            data: $expenseCategoryService->update($expenseCategoryPayload, $expenseCategory),
            groups: [SerializationGroups::EXPENSE_CATEGORY_UPDATE],
        );
    }
}
