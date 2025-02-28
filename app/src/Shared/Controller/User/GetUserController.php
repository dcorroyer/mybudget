<?php

declare(strict_types=1);

namespace App\Shared\Controller\User;

use App\Shared\Api\AbstractApiController;
use App\Shared\Service\UserService;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/users')]
#[OA\Tag(name: 'User')]
class GetUserController extends AbstractApiController
{
    #[Route('/me', name: 'api_users_get', methods: Request::METHOD_GET)]
    #[OA\Get(
        path: '/api/users/me',
        description: 'Get the connected user information',
        summary: 'User authenticated',
        security: [['Bearer' => []]]
    )]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'User information successfully retrieved',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: 'data',
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', example: 1),
                        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'user@example.com'),
                        new OA\Property(property: 'firstName', type: 'string', example: 'John'),
                        new OA\Property(property: 'lastName', type: 'string', example: 'Smith'),
                        new OA\Property(property: 'roles', type: 'array', items: new OA\Items(type: 'string'), example: ['ROLE_USER'])
                    ],
                    type: 'object'
                )
            ],
            type: 'object'
        )
    )]
    #[OA\Response(
        response: Response::HTTP_UNAUTHORIZED,
        description: 'User not authenticated',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'JWT Token not found'),
                new OA\Property(property: 'code', type: 'integer', example: 401)
            ],
            type: 'object'
        )
    )]
    public function __invoke(UserService $userService, #[CurrentUser] UserInterface $tokenUser): JsonResponse
    {
        return $this->successResponse(data: $userService->get($tokenUser->getUserIdentifier()));
    }
}
