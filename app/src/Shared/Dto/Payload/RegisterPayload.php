<?php

declare(strict_types=1);

namespace App\Shared\Dto\Payload;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

#[OA\Schema(
    schema: 'RegisterPayload',
    description: 'User registration data',
    required: ['email', 'firstName', 'lastName', 'password']
)]
class RegisterPayload
{
    #[Assert\NotBlank]
    #[Assert\Email]
    #[OA\Property(description: 'User email', example: 'user@example.com', type: 'string')]
    public string $email;

    #[Assert\NotBlank]
    #[OA\Property(description: 'User first name', example: 'John', type: 'string')]
    public string $firstName;

    #[Assert\NotBlank]
    #[OA\Property(description: 'User last name', example: 'Doe', type: 'string')]
    public string $lastName;

    #[Assert\NotBlank]
    #[OA\Property(description: 'User password', example: 'securePassword123', type: 'string', format: 'password')]
    public string $password;
}
