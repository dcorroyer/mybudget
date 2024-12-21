<?php

declare(strict_types=1);

namespace App\Account\Controller;

use App\Account\Dto\Payload\AccountPayload;
use App\Account\Dto\Response\AccountResponse;
use App\Account\Entity\Account;
use App\Account\Service\AccountService;
use App\Shared\Api\AbstractApiController;
use App\Shared\Api\Nelmio\Attribute\SuccessResponse;
use OpenApi\Attributes\Tag;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/accounts')]
#[Tag(name: 'Accounts')]
class UpdateAccountController extends AbstractApiController
{
    #[SuccessResponse(dataFqcn: AccountResponse::class, description: 'update an account')]
    #[Route('/{id}', name: __METHOD__, methods: Request::METHOD_PATCH)]
    public function __invoke(
        AccountService $accountService,
        Account $account,
        #[MapRequestPayload] AccountPayload $accountPayload
    ): JsonResponse {
        return $this->successResponse(data: $accountService->update($accountPayload, $account));
    }
}
