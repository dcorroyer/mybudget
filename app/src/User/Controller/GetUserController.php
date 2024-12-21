<?php

declare(strict_types=1);

namespace App\User\Controller;

use App\Shared\Api\AbstractApiController;
use App\Shared\Api\Nelmio\Attribute\SuccessResponse;
use App\User\Dto\Response\UserResponse;
use App\User\Service\UserService;
use OpenApi\Attributes\Tag;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/users')]
#[Tag(name: 'Users')]
class GetUserController extends AbstractApiController
{
    #[SuccessResponse(dataFqcn: UserResponse::class, description: 'Get the current user')]
    #[Route('/me', name: __METHOD__, methods: Request::METHOD_GET)]
    public function __invoke(UserService $userService, #[CurrentUser] UserInterface $tokenUser): JsonResponse
    {
        return $this->successResponse(data: $userService->get($tokenUser->getUserIdentifier()));
    }
}
