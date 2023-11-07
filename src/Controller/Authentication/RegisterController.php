<?php

declare(strict_types=1);

namespace App\Controller\Authentication;

use App\Dto\User\Payload\RegisterPayload;
use App\Serializable\SerializationGroups;
use App\Service\UserService;
use My\RestBundle\Controller\BaseRestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends BaseRestController
{
    #[Route('/register', name: 'app_register', methods: ['POST'])]
    public function register(
        UserService $userService,
        #[MapRequestPayload] RegisterPayload $payload
    ): JsonResponse {
        return $this->successResponse(
            data: $userService->create($payload),
            groups: [SerializationGroups::USER_CREATE],
            status: Response::HTTP_CREATED,
        );
    }
}
