<?php

declare(strict_types=1);

namespace App\Controller\Account;

use App\Dto\Account\Response\AccountResponse;
use App\Service\AccountService;
use My\RestBundle\Attribute\MyOpenApi\MyOpenApi;
use My\RestBundle\Attribute\MyOpenApi\Response\SuccessResponse;
use App\Controller\BaseRestController;
use My\RestBundle\Dto\PaginationQueryParams;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/accounts')]
#[OA\Tag(name: 'Accounts')]
class ListAccountController extends BaseRestController
{
    #[MyOpenApi(
        httpMethod: Request::METHOD_GET,
        operationId: 'list_account',
        summary: 'list account',
        responses: [
            new SuccessResponse(responseClassFqcn: AccountResponse::class, description: 'Return the list of accounts'),
        ],
        queryParamsClassFqcn: [PaginationQueryParams::class],
    )]
    #[Route('', name: 'api_accounts_list', methods: Request::METHOD_GET)]
    public function __invoke(AccountService $accountService): JsonResponse
    {
        return $this->successResponse(data: $accountService->listExternal());
    }
}
