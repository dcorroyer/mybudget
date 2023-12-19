<?php

declare(strict_types=1);

namespace App\Controller\Income;

use App\Dto\Income\Http\IncomeFilterQuery;
use App\Entity\Income;
use App\Serializable\SerializationGroups;
use App\Service\IncomeService;
use My\RestBundle\Attribute\MyOpenApi\MyOpenApi;
use My\RestBundle\Attribute\MyOpenApi\Response\PaginatedSuccessResponse;
use My\RestBundle\Controller\BaseRestController;
use My\RestBundle\Dto\PaginationQueryParams;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/incomes')]
#[OA\Tag(name: 'Incomes')]
class ListIncomeController extends BaseRestController
{
    #[MyOpenApi(
        httpMethod: Request::METHOD_GET,
        operationId: 'list_income',
        summary: 'list income',
        responses: [
            new paginatedSuccessResponse(
                responseClassFqcn: Income::class,
                groups: [SerializationGroups::INCOME_LIST],
                description: 'Return the paginated list of incomes'
            ),
        ],
        queryParamsClassFqcn: [IncomeFilterQuery::class, PaginationQueryParams::class],
    )]
    #[Route('', name: 'api_incomes_list', methods: Request::METHOD_GET)]
    public function __invoke(
        IncomeService $incomeService,
        #[MapQueryString] PaginationQueryParams $paginationQueryParams = null,
        #[MapQueryString] IncomeFilterQuery $filter = null,
    ): JsonResponse {
        return $this->paginateResponse(
            $incomeService->paginate($paginationQueryParams, $filter),
            [SerializationGroups::INCOME_LIST],
        );
    }
}
