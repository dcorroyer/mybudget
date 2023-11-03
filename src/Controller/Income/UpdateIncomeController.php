<?php

declare(strict_types=1);

namespace App\Controller\Income;

use App\Controller\BaseRestController;
use App\Dto\Income\Payload\IncomePayload;
use App\Entity\Income;
use App\Serializable\SerializationGroups;
use App\Service\IncomeService;
use My\OpenApiBundle\Attribute\MyOpenApi\MyOpenApi;
use My\OpenApiBundle\Attribute\MyOpenApi\Response\SuccessResponse;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/incomes')]
#[OA\Tag(name: 'Incomes')]
class UpdateIncomeController extends BaseRestController
{
    #[MyOpenApi(
        httpMethod: Request::METHOD_PUT,
        operationId: 'put_income',
        summary: 'put income',
        responses: [
            new successResponse(
                responseClassFqcn: Income::class,
                groups: [SerializationGroups::INCOME_UPDATE],
                description: 'Income update',
            ),
        ],
        requestBodyClassFqcn: IncomePayload::class
    )]
    #[Route('/{id}', name: 'app_incomes_update', methods: Request::METHOD_PUT)]
    public function update(
        IncomeService $incomeService,
        #[MapRequestPayload] IncomePayload $incomePayload,
        Income $income,
    ): JsonResponse {
        $incomeUpdated = $incomeService->update($incomePayload, $income);

        return $this->apiResponse(data: $incomeUpdated, groups: [SerializationGroups::INCOME_UPDATE]);
    }
}
