<?php

declare(strict_types=1);

namespace App\Savings\Controller\Account;

use App\Savings\Entity\Account;
use App\Savings\Service\AccountService;
use App\Shared\Api\AbstractApiController;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/accounts')]
#[OA\Tag(name: 'Accounts')]
class DeleteAccountController extends AbstractApiController
{
    #[Route('/{id}', name: 'api_accounts_delete', methods: Request::METHOD_DELETE)]
    #[OA\Delete(
        path: '/api/accounts/{id}',
        description: 'Delete an existing account',
        summary: 'Delete an account',
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Account ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(response: Response::HTTP_NO_CONTENT, description: 'Account successfully deleted'),
            new OA\Response(
                response: Response::HTTP_NOT_FOUND,
                description: 'Account not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Account not found'),
                        new OA\Property(property: 'code', type: 'integer', example: 404),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(
                response: Response::HTTP_BAD_REQUEST,
                description: 'Unable to delete account',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'message',
                            type: 'string',
                            example: 'Cannot delete account with existing transactions'
                        ),
                        new OA\Property(property: 'code', type: 'integer', example: 400),
                    ],
                    type: 'object'
                )
            ),
        ]
    )]
    public function __invoke(AccountService $accountService, Account $account): JsonResponse
    {
        $accountService->delete($account);

        return $this->noContentResponse();
    }
}
