<?php

declare(strict_types=1);

namespace App\Controller\Authentication;

use App\Dto\User\Payload\RegisterPayload;
use App\Dto\User\Response\UserResponse;
use App\Service\UserService;
use App\Shared\Api\AbstractApiController;
use App\Shared\Api\Nelmio\Attribute\SuccessResponse;
use OpenApi\Attributes\Tag;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Tag(name: 'Authentication')]
class RegisterController extends AbstractApiController
{
    #[SuccessResponse(dataFqcn: UserResponse::class, description: 'Create a user')]
    #[Route('/register', name: __METHOD__, methods: Request::METHOD_POST)]
    public function __invoke(UserService $userService, #[MapRequestPayload] RegisterPayload $payload): JsonResponse
    {
        return $this->successResponse(data: $userService->create($payload), status: Response::HTTP_CREATED);
    }
}
