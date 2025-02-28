<?php

declare(strict_types=1);

namespace App\Savings\Controller\Account;

use App\Savings\Dto\Payload\AccountPayload;
use App\Savings\Entity\Account;
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
class UpdateAccountController extends AbstractApiController
{
    #[Route('/{id}', name: 'api_accounts_update', methods: Request::METHOD_PATCH)]
    #[OA\Patch(
        path: '/api/accounts/{id}',
        description: 'Update an existing account',
        summary: 'Update an account'
    )]
    #[OA\Parameter(
        name: 'id',
        description: 'Account ID',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\RequestBody(
        description: 'Account data to update',
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'name', type: 'string', example: 'Modified Savings Account')
            ]
        )
    )]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Account successfully updated',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: 'data',
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', example: 1),
                        new OA\Property(property: 'name', type: 'string', example: 'Modified Savings Account'),
                        new OA\Property(property: 'type', type: 'string', example: 'savings'),
                        new OA\Property(property: 'balance', type: 'number', format: 'float', example: 2500.50)
                    ],
                    type: 'object'
                )
            ],
            type: 'object'
        )
    )]
    #[OA\Response(
        response: Response::HTTP_NOT_FOUND,
        description: 'Account not found',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'Account not found'),
                new OA\Property(property: 'code', type: 'integer', example: 404)
            ],
            type: 'object'
        )
    )]
    #[OA\Response(
        response: Response::HTTP_BAD_REQUEST,
        description: 'Invalid data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'Validation failed'),
                new OA\Property(property: 'code', type: 'integer', example: 400)
            ],
            type: 'object'
        )
    )]
    public function __invoke(
        Account $account,
        AccountService $accountService,
        #[MapRequestPayload] AccountPayload $accountPayload
    ): JsonResponse {
        return $this->successResponse(data: $accountService->update($accountPayload, $account));
    }
}
