<?php

declare(strict_types=1);

namespace App\Account\Controller;

use App\Account\Dto\Payload\AccountPayload;
use App\Account\Dto\Response\AccountResponse;
use App\Account\Service\AccountService;
use App\Shared\Api\AbstractApiController;
use App\Shared\Api\Nelmio\Attribute\SuccessResponse;
use OpenApi\Attributes\Tag;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/accounts')]
#[Tag(name: 'Accounts')]
class CreateAccountController extends AbstractApiController
{
    #[SuccessResponse(dataFqcn: AccountResponse::class, description: 'Create an account')]
    #[Route('', name: __METHOD__, methods: Request::METHOD_POST)]
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
