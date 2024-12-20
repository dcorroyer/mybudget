<?php

declare(strict_types=1);

namespace App\Shared\Api\Dto\Response;

class ErrorResponse extends AbstractApiResponse
{
    /**
     * @param array<mixed> $errors
     */
    private function __construct(
        public string $message,
        public int $code,
        public array $errors = [],
    ) {
    }

    /**
     * @param array<mixed> $errors
     */
    public static function create(string $message, int $code, array $errors = []): self
    {
        return new self(message: $message, code: $code, errors: $errors);
    }
}
