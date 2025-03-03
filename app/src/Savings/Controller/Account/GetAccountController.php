<?php

declare(strict_types=1);

namespace App\Savings\Controller\Account;

use App\Savings\Dto\Response\AccountResponse;
use App\Savings\Service\AccountService;
use App\Shared\Api\AbstractApiController;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/accounts')]
#[OA\Tag(name: 'Accounts')]
class GetAccountController extends AbstractApiController
{
    #[Route('/{id}', name: 'api_accounts_get', methods: Request::METHOD_GET)]
    #[OA\Get(
        path: '/api/accounts/{id}',
        description: 'Retrieve an account by its ID',
        summary: 'Get an account',
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
            new OA\Response(
                response: Response::HTTP_OK,
                description: 'Account successfully retrieved',
                content: new OA\JsonContent(
                    properties: [new OA\Property(property: 'data', ref: new Model(type: AccountResponse::class))],
                    type: 'object'
                )
            ),
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
        ]
    )]
    public function __invoke(int $id, AccountService $accountService): JsonResponse
    {
        return $this->successResponse(data: $accountService->getExternal($id));
    }
}
