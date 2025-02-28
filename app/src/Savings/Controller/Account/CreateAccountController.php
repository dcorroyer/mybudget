<?php

declare(strict_types=1);

namespace App\Savings\Controller\Account;

use App\Savings\Dto\Payload\AccountPayload;
use App\Savings\Dto\Response\AccountResponse;
use App\Savings\Service\AccountService;
use App\Shared\Api\AbstractApiController;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/accounts')]
#[OA\Tag(name: 'Accounts')]
class CreateAccountController extends AbstractApiController
{
    #[Route('', name: 'api_accounts_create', methods: Request::METHOD_POST)]
    #[OA\Post(
        path: '/api/accounts',
        description: 'Create a new account',
        summary: 'Create an account'
    )]
    #[OA\RequestBody(
        description: 'Account data to create',
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'name', type: 'string', example: 'Current Account', description: 'Account name')
            ]
        )
    )]
    #[OA\Response(
        response: Response::HTTP_CREATED,
        description: 'Account successfully created',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: 'data',
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', example: 1),
                        new OA\Property(property: 'name', type: 'string', example: 'Account'),
                        new OA\Property(property: 'type', type: 'string', example: 'savings'),
                        new OA\Property(property: 'balance', type: 'number', format: 'float', example: 0.0)
                    ],
                    type: 'object'
                )
            ],
            type: 'object'
        )
    )]
    #[OA\Response(
        response: Response::HTTP_BAD_REQUEST,
        description: 'Invalid data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'Validation failed')
            ],
            type: 'object'
        )
    )]
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
