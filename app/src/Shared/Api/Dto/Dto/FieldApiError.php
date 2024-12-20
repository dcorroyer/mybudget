<?php

declare(strict_types=1);

namespace App\Shared\Api\Dto\Dto;

class FieldApiError extends ApiError
{
    public function __construct(
        public string $field,
        string $message,
        int $code,
    ) {
        parent::__construct($message, $code);
    }
}
