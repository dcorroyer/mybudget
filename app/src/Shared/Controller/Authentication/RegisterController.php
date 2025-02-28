<?php

declare(strict_types=1);

namespace App\Shared\Controller\Authentication;

use App\Shared\Api\AbstractApiController;
use App\Shared\Dto\Payload\RegisterPayload;
use App\Shared\Service\UserService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class RegisterController extends AbstractApiController
{
    #[Route('/register', name: 'api_register', methods: Request::METHOD_POST)]
    public function __invoke(UserService $userService, #[MapRequestPayload] RegisterPayload $payload): JsonResponse
    {
        return $this->successResponse(data: $userService->create($payload), status: Response::HTTP_CREATED);
    }
}
