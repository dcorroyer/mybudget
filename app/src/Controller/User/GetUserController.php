<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Entity\User;
use App\Service\UserService;
use My\RestBundle\Attribute\MyOpenApi\MyOpenApi;
use My\RestBundle\Attribute\MyOpenApi\Response\NotFoundResponse;
use My\RestBundle\Attribute\MyOpenApi\Response\SuccessResponse;
use My\RestBundle\Controller\BaseRestController;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/users')]
#[OA\Tag(name: 'Users')]
class GetUserController extends BaseRestController
{
    #[MyOpenApi(
        httpMethod: Request::METHOD_GET,
        operationId: 'get_user',
        summary: 'get user',
        responses: [
            new SuccessResponse(responseClassFqcn: User::class, description: 'User get'),
            new NotFoundResponse(description: 'User not found'),
        ],
    )]
    #[Route('/me', name: 'api_users_get', methods: Request::METHOD_GET)]
    public function __invoke(UserService $userService, #[CurrentUser] UserInterface $tokenUser): JsonResponse
    {
        return $this->successResponse(data: $userService->get($tokenUser->getUserIdentifier()));
    }
}
