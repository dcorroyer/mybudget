<?php

declare(strict_types=1);

namespace App\Shared\Controller\Authentication;

use App\Shared\Api\AbstractApiController;
use App\Shared\Dto\Payload\RegisterPayload;
use App\Shared\Dto\Response\UserResponse;
use App\Shared\Service\UserService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[OA\Tag(name: 'Authentication')]
class RegisterController extends AbstractApiController
{
    #[Route('/register', name: 'api_register', methods: Request::METHOD_POST)]
    #[OA\Post(
        path: '/api/register',
        description: 'Allows a user to create an account',
        summary: 'User registration'
    )]
    #[OA\RequestBody(
        description: 'User registration data',
        required: true,
        content: new OA\JsonContent(ref: new Model(type: RegisterPayload::class))
    )]
    #[OA\Response(
        response: Response::HTTP_CREATED,
        description: 'User successfully created',
        content: new OA\JsonContent(
            properties: [new OA\Property(property: 'data', ref: new Model(type: UserResponse::class))],
            type: 'object'
        )
    )]
    #[OA\Response(
        response: Response::HTTP_BAD_REQUEST,
        description: 'Invalid data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'Email already exists'),
                new OA\Property(property: 'code', type: 'integer', example: 400),
            ],
            type: 'object'
        )
    )]
    public function __invoke(UserService $userService, #[MapRequestPayload] RegisterPayload $payload): JsonResponse
    {
        return $this->successResponse(data: $userService->create($payload), status: Response::HTTP_CREATED);
    }
}
