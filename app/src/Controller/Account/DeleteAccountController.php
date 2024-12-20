<?php

declare(strict_types=1);

namespace App\Controller\Account;

use App\Entity\Account;
use App\Service\AccountService;
use App\Shared\Api\AbstractApiController;
use App\Shared\Api\Nelmio\Attribute\NoContentResponse;
use OpenApi\Attributes\Tag;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/accounts')]
#[Tag(name: 'Accounts')]
class DeleteAccountController extends AbstractApiController
{
    #[NoContentResponse(description: 'Delete an account')]
    #[Route('/{id}', name: __METHOD__, methods: Request::METHOD_DELETE)]
    public function __invoke(AccountService $accountService, Account $account): JsonResponse
    {
        $accountService->delete($account);

        return $this->noContentResponse();
    }
}
