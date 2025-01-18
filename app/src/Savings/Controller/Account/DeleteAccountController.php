<?php

declare(strict_types=1);

namespace App\Savings\Controller\Account;

use App\Core\Api\AbstractApiController;
use App\Savings\Entity\Account;
use App\Savings\Service\AccountService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/accounts')]
class DeleteAccountController extends AbstractApiController
{
    #[Route('/{id}', name: 'api_accounts_delete', methods: Request::METHOD_DELETE)]
    public function __invoke(AccountService $accountService, Account $account): JsonResponse
    {
        $accountService->delete($account);

        return $this->noContentResponse();
    }
}
