<?php

declare(strict_types=1);

namespace App\Controller\Account;

use App\Dto\Account\Payload\AccountPayload;
use App\Dto\Account\Response\AccountResponse;
use App\Service\AccountService;
use My\RestBundle\Attribute\MyOpenApi\MyOpenApi;
use My\RestBundle\Attribute\MyOpenApi\Response\SuccessResponse;
use My\RestBundle\Controller\BaseRestController;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/accounts')]
#[OA\Tag(name: 'Accounts')]
class CreateAccountController extends BaseRestController
{
    #[MyOpenApi(
        httpMethod: Request::METHOD_POST,
        operationId: 'post_account',
        summary: 'post account',
        responses: [
            new SuccessResponse(
                responseClassFqcn: AccountResponse::class,
                responseCode: Response::HTTP_CREATED,
                description: 'Account creation',
            ),
        ],
        requestBodyClassFqcn: AccountPayload::class
    )]
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
