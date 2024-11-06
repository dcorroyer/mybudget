<?php

declare(strict_types=1);

namespace App\Controller\Account;

use App\Entity\Account;
use App\Service\AccountService;
use My\RestBundle\Attribute\MyOpenApi\MyOpenApi;
use My\RestBundle\Attribute\MyOpenApi\Response\NotFoundResponse;
use My\RestBundle\Attribute\MyOpenApi\Response\SuccessResponse;
use My\RestBundle\Controller\BaseRestController;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/accounts')]
#[OA\Tag(name: 'Accounts')]
class GetAccountController extends BaseRestController
{
    #[MyOpenApi(
        httpMethod: Request::METHOD_GET,
        operationId: 'get_account',
        summary: 'get account',
        responses: [
            new SuccessResponse(responseClassFqcn: Account::class, description: 'Account get'),
            new NotFoundResponse(description: 'Account not found'),
        ],
    )]
    #[Route('/{id}', name: 'api_accounts_get', methods: Request::METHOD_GET)]
    public function __invoke(int $id, AccountService $accountService): JsonResponse
    {
        return $this->successResponse(data: $accountService->get($id));
    }
}
