<?php

declare(strict_types=1);

namespace App\Savings\Controller\Account;

use App\Core\Api\AbstractApiController;
use App\Savings\Service\AccountService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/accounts')]
class GetAccountController extends AbstractApiController
{
    #[Route('/{id}', name: 'api_accounts_get', methods: Request::METHOD_GET)]
    public function __invoke(int $id, AccountService $accountService): JsonResponse
    {
        return $this->successResponse(data: $accountService->getExternal($id));
    }
}
