<?php

declare(strict_types=1);

namespace App\Controller\Income;

use App\Controller\BaseRestController;
use App\Entity\Income;
use App\Serializable\SerializationGroups;
use App\Service\IncomeService;
use My\OpenApiBundle\Attribute\MyOpenApi\MyOpenApi;
use My\OpenApiBundle\Attribute\MyOpenApi\Response\NotFoundResponse;
use My\OpenApiBundle\Attribute\MyOpenApi\Response\SuccessResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/incomes')]
class DeleteIncomeController extends BaseRestController
{
    #[MyOpenApi(
        httpMethod: Request::METHOD_DELETE,
        operationId: 'delete_income',
        summary: 'delete income',
        responses: [
            new successResponse(
                responseClassFqcn: Income::class,
                groups: [SerializationGroups::INCOME_DELETE],
                description: 'Income delete',
            ),
            new NotFoundResponse(description: 'Income not found'),
        ],
    )]
    #[Route('/{id}', name: 'app_incomes_delete', methods: Request::METHOD_DELETE)]
    public function delete(IncomeService $incomeService, Income $income): JsonResponse
    {
        $incomeDeleted = $incomeService->delete($income);

        return $this->apiResponse(
            data: $incomeDeleted,
            groups: [SerializationGroups::INCOME_DELETE],
            status: Response::HTTP_NO_CONTENT,
        );
    }
}
