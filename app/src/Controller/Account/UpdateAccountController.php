<?php

declare(strict_types=1);

namespace App\Controller\Account;

use App\Dto\Account\Payload\AccountPayload;
use App\Entity\Account;
use App\Serializable\SerializationGroups;
use App\Service\AccountService;
use My\RestBundle\Attribute\MyOpenApi\MyOpenApi;
use My\RestBundle\Attribute\MyOpenApi\Response\NotFoundResponse;
use My\RestBundle\Attribute\MyOpenApi\Response\SuccessResponse;
use My\RestBundle\Controller\BaseRestController;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/accounts')]
#[OA\Tag(name: 'Accounts')]
class UpdateAccountController extends BaseRestController
{
    #[MyOpenApi(
        httpMethod: Request::METHOD_PATCH,
        operationId: 'put_account',
        summary: 'put account',
        responses: [
            new SuccessResponse(
                responseClassFqcn: Account::class,
                groups: [SerializationGroups::ACCOUNT_UPDATE],
                description: 'Account updated',
            ),
            new NotFoundResponse(description: 'Account not found'),
        ],
        requestBodyClassFqcn: AccountPayload::class
    )]
    #[Route('/{id}', name: 'api_account_update', methods: Request::METHOD_PATCH)]
    public function __invoke(
        AccountService $accountService,
        Account $account,
        #[MapRequestPayload] AccountPayload $accountPayload
    ): JsonResponse {
        return $this->successResponse(
            data: $accountService->update($accountPayload, $account),
            groups: [SerializationGroups::ACCOUNT_UPDATE]
        );
    }
}
