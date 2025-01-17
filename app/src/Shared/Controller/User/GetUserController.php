<?php

declare(strict_types=1);

namespace App\Shared\Controller\User;

use App\Core\Api\AbstractApiController;
use App\Shared\Service\UserService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/users')]
class GetUserController extends AbstractApiController
{
    #[Route('/me', name: 'api_users_get', methods: Request::METHOD_GET)]
    public function __invoke(UserService $userService, #[CurrentUser] UserInterface $tokenUser): JsonResponse
    {
        return $this->successResponse(data: $userService->get($tokenUser->getUserIdentifier()));
    }
}
