<?php

declare(strict_types=1);

namespace App\Controller\Income;

use App\Entity\Income;
use App\Serializable\SerializationGroups;
use My\RestBundle\Attribute\MyOpenApi\MyOpenApi;
use My\RestBundle\Attribute\MyOpenApi\Response\NotFoundResponse;
use My\RestBundle\Attribute\MyOpenApi\Response\SuccessResponse;
use My\RestBundle\Controller\BaseRestController;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/incomes')]
#[OA\Tag(name: 'Incomes')]
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
    #[Route('/{id}', name: 'api_incomes_get', methods: Request::METHOD_GET)]
    public function __invoke(Income $income): JsonResponse
    {
        return $this->successResponse(data: $income, groups: [SerializationGroups::INCOME_GET]);
    }
}
