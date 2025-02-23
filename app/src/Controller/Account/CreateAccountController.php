<?php

declare(strict_types=1);

namespace App\Controller\Account;

use App\Core\Api\AbstractApiController;
use App\Dto\Savings\Payload\AccountPayload;
use App\Service\AccountService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/accounts')]
class CreateAccountController extends AbstractApiController
{
    #[Route('', name: 'api_accounts_create', methods: Request::METHOD_POST)]
    public function __invoke(
        AccountService $accountService,
        #[MapRequestPayload] AccountPayload $accountPayload
    ): JsonResponse {
        return $this->successResponse(
            data: $accountService->create($accountPayload),
            status: Response::HTTP_CREATED,
        );
    }
}
