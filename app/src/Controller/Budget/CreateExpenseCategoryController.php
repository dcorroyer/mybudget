<?php

declare(strict_types=1);

namespace App\Controller\Budget;

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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/expense-categories')]
#[OA\Tag(name: 'Expense Categories')]
class CreateExpenseCategoryController extends BaseRestController
{
    #[MyOpenApi(
        httpMethod: Request::METHOD_POST,
        operationId: 'post_expense_category',
        summary: 'post expense category',
        responses: [
            new SuccessResponse(
                responseClassFqcn: ExpenseCategory::class,
                groups: [SerializationGroups::EXPENSE_CATEGORY_CREATE],
                responseCode: Response::HTTP_CREATED,
                description: 'Expense Category creation',
            ),
        ],
        requestBodyClassFqcn: ExpenseCategoryPayload::class
    )]
    #[Route('', name: 'api_expense_categories_create', methods: Request::METHOD_POST)]
    public function __invoke(
        ExpenseCategoryService $expenseCategoryService,
        #[MapRequestPayload] ExpenseCategoryPayload $expenseCategoryPayload
    ): JsonResponse {
        return $this->successResponse(
            data: $expenseCategoryService->create($expenseCategoryPayload),
            groups: [SerializationGroups::EXPENSE_CATEGORY_CREATE],
            status: Response::HTTP_CREATED,
        );
    }
}
