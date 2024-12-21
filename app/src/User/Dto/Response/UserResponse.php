<?php

declare(strict_types=1);

namespace App\User\Dto\Response;

use My\RestBundle\Contract\ResponseInterface;

class UserResponse implements ResponseInterface
{
    public function __construct(
        public readonly int $id,
        public readonly string $email,
        public readonly string $firstName,
        public readonly string $lastName,
    ) {
    }
}
