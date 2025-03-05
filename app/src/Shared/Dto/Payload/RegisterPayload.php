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
    #[OA\Property(description: 'User email', type: 'string', example: 'user@example.com')]
    public string $email;

    #[Assert\NotBlank]
    #[OA\Property(description: 'User first name', type: 'string', example: 'John')]
    public string $firstName;

    #[Assert\NotBlank]
    #[OA\Property(description: 'User last name', type: 'string', example: 'Doe')]
    public string $lastName;

    #[Assert\NotBlank]
    #[OA\Property(description: 'User password', type: 'string', format: 'password', example: 'securePassword123')]
    public string $password;
}
