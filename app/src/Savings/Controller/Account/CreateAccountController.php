<?php

declare(strict_types=1);

namespace App\Savings\Controller\Account;

use App\Savings\Dto\Payload\AccountPayload;
use App\Savings\Dto\Response\AccountResponse;
use App\Savings\Service\AccountService;
use App\Shared\Api\AbstractApiController;
use Nelmio\ApiDocBundle\Annotation\Model;
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
        summary: 'Create an account',
        requestBody: new OA\RequestBody(
            description: 'Account data to create',
            required: true,
            content: new OA\JsonContent(ref: new Model(type: AccountPayload::class))
        ),
        responses: [
            new OA\Response(
                response: Response::HTTP_CREATED,
                description: 'Account successfully created',
                content: new OA\JsonContent(
                    properties: [new OA\Property(property: 'data', ref: new Model(type: AccountResponse::class))],
                    type: 'object'
                )
            ),
            new OA\Response(
                response: Response::HTTP_BAD_REQUEST,
                description: 'Invalid data',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Validation failed'),
                        new OA\Property(property: 'code', type: 'integer', example: 400),
                    ],
                    type: 'object'
                )
            ),
        ]
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
