<?php

declare(strict_types=1);

namespace App\Controller\Account;

use App\Dto\Account\Response\AccountResponse;
use App\Entity\Account;
use App\Service\AccountService;
use My\RestBundle\Attribute\MyOpenApi\MyOpenApi;
use My\RestBundle\Attribute\MyOpenApi\Response\NotFoundResponse;
use My\RestBundle\Attribute\MyOpenApi\Response\SuccessResponse;
use App\Controller\BaseRestController;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/accounts')]
#[OA\Tag(name: 'Accounts')]
class DeleteAccountController extends BaseRestController
{
    #[MyOpenApi(
        httpMethod: Request::METHOD_DELETE,
        operationId: 'delete_account',
        summary: 'delete account',
        responses: [
            new SuccessResponse(
                responseClassFqcn: AccountResponse::class,
                responseCode: Response::HTTP_NO_CONTENT,
                description: 'Account deleted'
            ),
            new NotFoundResponse(description: 'Account not found'),
        ],
    )]
    #[Route('/{id}', name: 'api_accounts_delete', methods: Request::METHOD_DELETE)]
    public function __invoke(AccountService $accountService, Account $account): JsonResponse
    {
        $accountService->delete($account);

        return $this->successResponse(data: [], status: Response::HTTP_NO_CONTENT);
    }
}
