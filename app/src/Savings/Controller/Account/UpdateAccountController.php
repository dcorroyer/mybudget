<?php

declare(strict_types=1);

namespace App\Savings\Controller\Account;

use App\Core\Api\AbstractApiController;
use App\Savings\Dto\Payload\AccountPayload;
use App\Savings\Entity\Account;
use App\Savings\Service\AccountService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/accounts')]
class UpdateAccountController extends AbstractApiController
{
    #[Route('/{id}', name: 'api_account_update', methods: Request::METHOD_PATCH)]
    public function __invoke(
        AccountService $accountService,
        Account $account,
        #[MapRequestPayload] AccountPayload $accountPayload
    ): JsonResponse {
        return $this->successResponse(data: $accountService->update($accountPayload, $account));
    }
}
