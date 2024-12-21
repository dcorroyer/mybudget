<?php

declare(strict_types=1);

namespace App\Account\Controller;

use App\Account\Dto\Response\AccountResponse;
use App\Account\Service\AccountService;
use App\Shared\Api\AbstractApiController;
use App\Shared\Api\Nelmio\Attribute\SuccessResponse;
use OpenApi\Attributes\Tag;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/accounts')]
#[Tag(name: 'Accounts')]
class GetAccountController extends AbstractApiController
{
    #[SuccessResponse(dataFqcn: AccountResponse::class, description: 'Get an account')]
    #[Route('/{id}', name: __METHOD__, methods: Request::METHOD_GET)]
    public function __invoke(int $id, AccountService $accountService): JsonResponse
    {
        return $this->successResponse(data: $accountService->getExternal($id));
    }
}
