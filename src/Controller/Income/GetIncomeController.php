<?php

declare(strict_types=1);

namespace App\Controller\Income;

use App\Controller\BaseRestController;
use App\Entity\Income;
use App\Serializable\SerializationGroups;
use My\OpenApiBundle\Attribute\MyOpenApi\MyOpenApi;
use My\OpenApiBundle\Attribute\MyOpenApi\Response\NotFoundResponse;
use My\OpenApiBundle\Attribute\MyOpenApi\Response\SuccessResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/incomes')]
class GetIncomeController extends BaseRestController
{
    #[MyOpenApi(
        httpMethod: Request::METHOD_GET,
        operationId: 'get_income',
        summary: 'get income',
        responses: [
            new successResponse(
                responseClassFqcn: Income::class,
                groups: [SerializationGroups::INCOME_GET],
                description: 'Income get',
            ),
            new notfoundResponse(description: 'Income not found'),
        ],
    )]
    #[Route('/{id}', name: 'app_incomes_get', methods: Request::METHOD_GET)]
    public function get(Income $income): JsonResponse
    {
        return $this->apiResponse(data: $income, groups: [SerializationGroups::INCOME_GET]);
    }
}
