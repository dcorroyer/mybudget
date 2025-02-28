<?php

declare(strict_types=1);

namespace App\Savings\Controller\Account;

use App\Savings\Dto\Response\AccountResponse;
use App\Savings\Service\AccountService;
use App\Shared\Api\AbstractApiController;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/accounts')]
#[OA\Tag(name: 'Accounts')]
class ListAccountController extends AbstractApiController
{
    #[Route('', name: 'api_accounts_list', methods: Request::METHOD_GET)]
    #[OA\Get(
        path: '/api/accounts',
        description: 'Get a list of all accounts',
        summary: 'List accounts'
    )]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Account list successfully retrieved',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: 'data',
                    type: 'array',
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: 'id', type: 'integer', example: 1),
                            new OA\Property(property: 'name', type: 'string', example: 'Account'),
                            new OA\Property(property: 'type', type: 'string', example: 'savings'),
                            new OA\Property(property: 'balance', type: 'number', format: 'float', example: 1250.75)
                        ],
                        type: 'object'
                    )
                )
            ],
            type: 'object'
        )
    )]
    public function __invoke(AccountService $accountService): JsonResponse
    {
        return $this->successResponse(data: $accountService->listExternal());
    }
}
