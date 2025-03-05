<?php

declare(strict_types=1);

namespace App\Shared\Dto\Response;

use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UserResponse', description: 'User data',)]
class UserResponse
{
    public function __construct(
        #[OA\Property(description: 'User identifier', type: 'integer', example: 1)]
        public readonly int $id,

        #[OA\Property(description: 'User email', type: 'string', example: 'user@example.com')]
        public readonly string $email,

        #[OA\Property(description: 'User first name', type: 'string', example: 'John')]
        public readonly string $firstName,

        #[OA\Property(description: 'User last name', type: 'string', example: 'Doe')]
        public readonly string $lastName,
    ) {
    }
}
