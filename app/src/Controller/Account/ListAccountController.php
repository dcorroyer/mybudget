<?php

declare(strict_types=1);

namespace App\Controller\Account;

use App\Core\Api\AbstractApiController;
use App\Service\AccountService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/accounts')]
class ListAccountController extends AbstractApiController
{
    #[Route('', name: 'api_accounts_list', methods: Request::METHOD_GET)]
    public function __invoke(AccountService $accountService): JsonResponse
    {
        return $this->successResponse(data: $accountService->listExternal());
    }
}
