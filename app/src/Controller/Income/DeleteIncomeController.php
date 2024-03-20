<?php

declare(strict_types=1);

namespace App\Controller\Income;

use App\Entity\Income;
use App\Serializable\SerializationGroups;
use App\Service\IncomeService;
use My\RestBundle\Attribute\MyOpenApi\MyOpenApi;
use My\RestBundle\Attribute\MyOpenApi\Response\NotFoundResponse;
use My\RestBundle\Attribute\MyOpenApi\Response\SuccessResponse;
use My\RestBundle\Controller\BaseRestController;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/incomes')]
#[OA\Tag(name: 'Incomes')]
class DeleteIncomeController extends BaseRestController
{
    #[MyOpenApi(
        httpMethod: Request::METHOD_DELETE,
        operationId: 'delete_income',
        summary: 'delete income',
        responses: [
            new SuccessResponse(responseClassFqcn: Income::class, groups: [SerializationGroups::INCOME_DELETE], description: 'Income delete'),
            new NotFoundResponse(description: 'Income not found'),
        ],
    )]
    #[Route('/{id}', name: 'api_incomes_delete', methods: Request::METHOD_DELETE)]
    public function __invoke(IncomeService $incomeService, Income $income): Response
    {
        $incomeService->delete($income);

        return $this->createNoContentResponse();
    }
}
