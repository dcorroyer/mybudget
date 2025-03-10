<?php

declare(strict_types=1);

namespace App\Shared\Dto;

class PaginatedResponseDto
{
    /**
     * @param array<int, mixed> $data
     */
    public function __construct(
        public readonly array $data,
        public readonly PaginationMetaDto $meta,
    ) {
    }
}
