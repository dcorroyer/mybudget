<?php

declare(strict_types=1);

namespace App\Shared\Dto\Response;

use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UserResponse', description: 'User data',)]
class UserResponse
{
    public function __construct(
        #[OA\Property(description: 'User identifier', example: 1, type: 'integer')]
        public readonly int $id,

        #[OA\Property(description: 'User email', example: 'user@example.com', type: 'string')]
        public readonly string $email,

        #[OA\Property(description: 'User first name', example: 'John', type: 'string')]
        public readonly string $firstName,

        #[OA\Property(description: 'User last name', example: 'Doe', type: 'string')]
        public readonly string $lastName,
    ) {
    }
}
