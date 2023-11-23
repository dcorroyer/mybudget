<?php

declare(strict_types=1);

namespace App\Controller\Expense;

use App\Entity\Expense;
use App\Serializable\SerializationGroups;
use My\RestBundle\Attribute\MyOpenApi\MyOpenApi;
use My\RestBundle\Attribute\MyOpenApi\Response\NotFoundResponse;
use My\RestBundle\Attribute\MyOpenApi\Response\SuccessResponse;
use My\RestBundle\Controller\BaseRestController;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/expenses')]
#[OA\Tag(name: 'Expenses')]
class GetExpenseController extends BaseRestController
{
    #[MyOpenApi(
        httpMethod: Request::METHOD_GET,
        operationId: 'get_expense',
        summary: 'get expense',
        responses: [
            new successResponse(
                responseClassFqcn: Expense::class,
                groups: [SerializationGroups::EXPENSE_GET],
                description: 'Expense get',
            ),
            new notfoundResponse(description: 'Expense not found'),
        ],
    )]
    #[Route('/{id}', name: 'app_expenses_get', methods: Request::METHOD_GET)]
    public function __invoke(Expense $expense): JsonResponse
    {
        return $this->successResponse(data: $expense, groups: [SerializationGroups::EXPENSE_GET]);
    }
}
