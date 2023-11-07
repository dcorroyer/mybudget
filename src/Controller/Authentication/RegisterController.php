<?php

declare(strict_types=1);

namespace App\Controller\Authentication;

use App\Dto\User\Payload\RegisterPayload;
use App\Entity\User;
use App\Serializable\SerializationGroups;
use App\Service\UserService;
use My\RestBundle\Attribute\MyOpenApi\MyOpenApi;
use My\RestBundle\Attribute\MyOpenApi\Response\SuccessResponse;
use My\RestBundle\Controller\BaseRestController;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Tag(name: 'Authentication')]
class RegisterController extends BaseRestController
{
    #[MyOpenApi(
        httpMethod: Request::METHOD_POST,
        operationId: 'post_register',
        summary: 'post register',
        responses: [
            new successResponse(
                responseClassFqcn: User::class,
                groups: [SerializationGroups::USER_CREATE],
                responseCode: Response::HTTP_CREATED,
                description: 'Income creation',
            ),
        ],
        requestBodyClassFqcn: RegisterPayload::class
    )]
    #[Route('/register', name: 'app_register', methods: Request::METHOD_POST)]
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
