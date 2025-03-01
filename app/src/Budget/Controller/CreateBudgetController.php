<?php

declare(strict_types=1);

namespace App\Budget\Controller;

use App\Budget\Dto\Payload\BudgetPayload;
use App\Budget\Dto\Response\BudgetResponse;
use App\Budget\Service\BudgetService;
use App\Shared\Api\AbstractApiController;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/budgets')]
#[OA\Tag(name: 'Budgets')]
class CreateBudgetController extends AbstractApiController
{
    #[Route('', name: 'api_budgets_create', methods: Request::METHOD_POST)]
    #[OA\Post(
        path: '/api/budgets',
        description: 'Create a new budget',
        summary: 'Create a budget',
        requestBody: new OA\RequestBody(
            description: 'Budget data to create',
            required: true,
            content: new OA\JsonContent(ref: new Model(type: BudgetPayload::class))
        ),
        responses: [
            new OA\Response(
                response: Response::HTTP_CREATED,
                description: 'Budget successfully created',
                content: new OA\JsonContent(
                    properties: [new OA\Property(property: 'data', ref: new Model(type: BudgetResponse::class))],
                    type: 'object'
                )
            ),
            new OA\Response(
                response: Response::HTTP_BAD_REQUEST,
                description: 'Invalid data',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Validation failed'),
                        new OA\Property(property: 'code', type: 'integer', example: 400),
                    ],
                    type: 'object'
                )
            ),
        ]
    )]
    public function __invoke(
        BudgetService $budgetService,
        #[MapRequestPayload] BudgetPayload $budgetPayload
    ): JsonResponse {
        return $this->successResponse(
            data: $budgetService->create($budgetPayload),
            status: Response::HTTP_CREATED,
        );
    }
}
