<?php

declare(strict_types=1);

namespace App\Controller\Income;

use App\Dto\Income\Payload\IncomePayload;
use App\Entity\Income;
use App\Serializable\SerializationGroups;
use App\Service\IncomeService;
use My\RestBundle\Attribute\MyOpenApi\MyOpenApi;
use My\RestBundle\Attribute\MyOpenApi\Response\SuccessResponse;
use My\RestBundle\Controller\BaseRestController;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/incomes')]
#[OA\Tag(name: 'Incomes')]
class CreateIncomeController extends BaseRestController
{
    #[MyOpenApi(
        httpMethod: Request::METHOD_POST,
        operationId: 'post_income',
        summary: 'post income',
        responses: [
            new successResponse(
                responseClassFqcn: Income::class,
                groups: [SerializationGroups::INCOME_CREATE],
                responseCode: Response::HTTP_CREATED,
                description: 'Income creation',
            ),
        ],
        requestBodyClassFqcn: IncomePayload::class
    )]
    #[Route('', name: 'api_incomes_create', methods: Request::METHOD_POST)]
    public function __invoke(
        IncomeService $incomeService,
        #[MapRequestPayload] IncomePayload $incomePayload
    ): JsonResponse {
        return $this->successResponse(
            data: $incomeService->create($incomePayload),
            groups: [SerializationGroups::INCOME_CREATE],
            status: Response::HTTP_CREATED,
        );
    }
}
