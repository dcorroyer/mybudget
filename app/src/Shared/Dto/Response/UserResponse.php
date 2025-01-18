<?php

declare(strict_types=1);

namespace App\Shared\Dto\Response;

class UserResponse
{
    public function __construct(
        public readonly int $id,
        public readonly string $email,
        public readonly string $firstName,
        public readonly string $lastName,
    ) {
    }
}
