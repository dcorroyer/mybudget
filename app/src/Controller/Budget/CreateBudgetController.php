<?php

declare(strict_types=1);

namespace App\Controller\Budget;

use App\Dto\Budget\Payload\BudgetPayload;
use App\Entity\Budget;
use App\Service\BudgetService;
use My\RestBundle\Attribute\MyOpenApi\MyOpenApi;
use My\RestBundle\Attribute\MyOpenApi\Response\SuccessResponse;
use My\RestBundle\Controller\BaseRestController;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/budgets')]
#[OA\Tag(name: 'Budgets')]
class CreateBudgetController extends BaseRestController
{
    #[MyOpenApi(
        httpMethod: Request::METHOD_POST,
        operationId: 'post_budget',
        summary: 'post budget',
        responses: [
            new SuccessResponse(
                responseClassFqcn: Budget::class,
                responseCode: Response::HTTP_CREATED,
                description: 'Budget creation',
            ),
        ],
        requestBodyClassFqcn: BudgetPayload::class
    )]
    #[Route('', name: 'api_budgets_create', methods: Request::METHOD_POST)]
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
